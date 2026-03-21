export interface Patient {
    id: number;
    name: string;
    surname: string;
    sex: string;
    birthDate: string;
}

export interface TestResult {
    name: string;
    value: string;
    reference: string;
}

export interface Order {
    orderId: string;
    results: TestResult[];
}

export interface ResultsResponse {
    patient: Patient;
    orders: Order[];
}
