<template>
  <div class="min-h-screen bg-gray-50 text-gray-800">
    <header class="flex items-center justify-between px-6 py-4 bg-white shadow">
      <h1 class="text-2xl font-semibold">Wyniki badań</h1>
      <button
          @click="logout"
          class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded hover:bg-red-600 transition"
      >
        Wyloguj
      </button>
    </header>

    <main class="max-w-5xl mx-auto mt-8 px-4">
      <div v-if="loading" class="text-center text-lg text-gray-600">
        Ładowanie...
      </div>

      <div v-else-if="error" class="text-center text-red-600 font-medium">
        {{ error }}
      </div>

      <div v-else-if="data" class="space-y-10">
        <section class="max-w-md mx-auto bg-white shadow-md rounded-2xl p-6 border border-gray-200">
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center text-white text-2xl font-bold">
              {{data.patient.name.charAt(0)}}{{data.patient.surname.charAt(0)}}
            </div>
            <h2 class="text-2xl font-semibold text-[#0033cc]">
              {{data.patient.name}} {{data.patient.surname}}
            </h2>
          </div>

          <div class="space-y-2 text-gray-700">
            <p><span class="font-semibold">ID:</span> {{data.patient.id}}</p>
            <p><span class="font-semibold">Płeć:</span> {{data.patient.sex}}</p>
            <p><span class="font-semibold">Data urodzenia:</span> {{data.patient.birthDate}}</p>
          </div>
        </section>

        <section>
          <h2 class="text-xl font-semibold border-b pb-2">Zamówienia</h2>

          <div
              v-for="order in data.orders"
              :key="order.orderId"
              class="mt-6 bg-white shadow rounded-lg p-6"
          >
            <h3 class="text-lg font-medium mb-4">
              Zamówienie #{{ order.orderId }}
            </h3>

            <table class="w-full border-collapse">
              <thead>
              <tr class="bg-gray-100 text-left">
                <th class="border px-3 py-2 font-medium">Nazwa</th>
                <th class="border px-3 py-2 font-medium">Wartość</th>
                <th class="border px-3 py-2 font-medium">Zakres referencyjny</th>
              </tr>
              </thead>

              <tbody>
              <tr
                  v-for="result in order.results"
                  :key="result.name"
                  class="hover:bg-gray-50 transition"
              >
                <td class="border px-3 py-2">{{ result.name }}</td>
                <td class="border px-3 py-2">{{ result.value }}</td>
                <td class="border px-3 py-2">{{ result.reference }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useResults } from '../composables/useResult';

const { data, loading, error, fetchResults, checkTokenExpiration, logout } = useResults();

onMounted(() => {
  checkTokenExpiration();
  fetchResults();
});
</script>
