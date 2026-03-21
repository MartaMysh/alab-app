<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Patient;
use App\Models\Order;
use App\Models\TestResult;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;
use Carbon\Carbon;

#[Signature('app:import-patient-data {file=results.csv}')]
#[Description('Import danych pacjentów z pliku CSV')]
class ImportPatientData extends Command
{
    public function handle()
    {
        $filePath = storage_path('app/' . $this->argument('file'));

        $successCount = 0;
        $errorCount = 0;

        if (!file_exists($filePath)) {
            $this->error("Plik nie istnieje: $filePath");
            Log::channel('import_errors')->error("Brak pliku", ['path' => $filePath]);
            return Command::FAILURE;
        }

        ini_set('auto_detect_line_endings', true);

        if (($handle = fopen($filePath, 'rb')) === false) {
            $this->error("Nie można otworzyć pliku CSV.");
            Log::channel('import_errors')->error("Nie można otworzyć pliku", ['path' => $filePath]);
            return Command::FAILURE;
        }

        $firstLine = fgets($handle);
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
        $headers = str_getcsv($firstLine, ';');

        if (!$headers) {
            $this->error("Nagłówek CSV jest niepoprawny.");
            Log::channel('import_errors')->error("Niepoprawny nagłówek CSV");
            return Command::FAILURE;
        }

        $expectedColumns = count($headers);

        if ($expectedColumns < 5) {
            $this->error("CSV wygląda na uszkodzony (za mało kolumn)");
            Log::channel('import_errors')->error("Uszkodzony CSV", ['columns' => $expectedColumns]);
            return Command::FAILURE;
        }

        $rowIndex = 1;

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $rowIndex++;

            if ($data === [null] || count(array_filter($data)) === 0) {
                continue;
            }

            if (count($data) !== $expectedColumns) {
                Log::channel('import_errors')->error("Zła liczba kolumn", [
                    'row' => $rowIndex,
                    'columns' => count($data),
                    'expected' => $expectedColumns
                ]);
                $errorCount++;
                continue;
            }

            $row = array_combine($headers, $data);

            try {
                $required = [
                    'patientId', 'patientName', 'patientSurname', 'patientSex',
                    'patientBirthDate', 'orderId', 'testName', 'testValue', 'testReference'
                ];

                $missingFields = [];

                foreach ($required as $field) {
                    if (empty($row[$field])) {
                        $missingFields[] = $field;
                    }
                }

                if (!empty($missingFields)) {
                    Log::channel('import_errors')->error("Brak pól", [
                        'row' => $rowIndex,
                        'missing' => $missingFields,
                        'data' => $row
                    ]);
                    $errorCount++;
                    continue;
                }

                if (!$this->checkBirthdayDate($row['patientBirthDate'])) {
                    Log::channel('import_errors')->error("Błędna data", [
                        'row' => $rowIndex,
                        'value' => $row['patientBirthDate']
                    ]);
                    $errorCount++;
                    continue;
                }

                $patient = Patient::where('patient_id', $row['patientId'])->first();

                if (!$patient) {
                    $patient = Patient::create([
                        'patient_id' => $row['patientId'],
                        'name' => $row['patientName'],
                        'surname' => $row['patientSurname'],
                        'sex' => $row['patientSex'],
                        'birth_date' => $row['patientBirthDate'],
                        'login' => $this->createLogin($row['patientName'], $row['patientSurname']),
                        'password' => Hash::make($row['patientBirthDate']),
                    ]);
                }

                $order = Order::firstOrCreate([
                    'order_identifier' => $row['orderId'],
                    'patient_id' => $patient->patient_id
                ]);

                TestResult::firstOrCreate(
                    [
                        'order_id' => $order->id,
                        'name' => $row['testName'],
                    ],
                    [
                        'value' => $row['testValue'],
                        'reference' => $row['testReference']
                    ]
                );

                Log::channel('import_success')->info("OK", [
                    'patient_id' => $patient->patient_id,
                    'test' => $row['testName'],
                    'row' => $rowIndex
                ]);

                $successCount++;

            } catch (\Exception $e) {
                Log::channel('import_errors')->error("Wyjątek importu", [
                    'row' => $rowIndex,
                    'error' => $e->getMessage(),
                    'data' => $row
                ]);
                $errorCount++;
            }
        }

        fclose($handle);

        $this->info("Import zakończony");
        $this->info("Poprawne rekordy: $successCount");
        $this->info("Błędy: $errorCount");

        Log::channel('import_success')->info("IMPORT SUMMARY", [
            'success' => $successCount,
            'errors' => $errorCount
        ]);

        return Command::SUCCESS;
    }

    private function checkBirthdayDate($date): bool
    {
        if (empty($date)) return false;

        try {
            $birthDate = Carbon::createFromFormat('Y-m-d', $date);
            return $birthDate->format('Y-m-d') === $date;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createLogin($firstname, $lastname): string
    {
        $login = $firstname . $lastname;

        $login = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $login);
        $login = preg_replace('/[^A-Za-z]/', '', $login);

        return $login;
    }
}