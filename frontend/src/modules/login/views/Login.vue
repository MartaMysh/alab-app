<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-100 px-6">
    <div class="w-full max-w-sm">
      <h2 class="mb-6 text-center text-2xl font-bold text-gray-900">
        Zaloguj się do swojego konta
      </h2>

      <div class="bg-white shadow-lg rounded-xl p-6">
        <Form
            :validation-schema="schema"
            :validate-on-input="true"
            class="space-y-6"
            v-slot="{ handleSubmit }"
        >
          <div>
            <label for="login" class="block text-sm font-medium text-gray-700">
              Login
            </label>
            <Field
                name="login"
                type="text"
                id="login"
                placeholder="tylko małe litery a–z"
                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900
                     placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            />
            <ErrorMessage name="login" class="text-red-500 text-xs mt-1" />
          </div>

          <!-- PASSWORD -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Hasło
            </label>
            <Field
                name="password"
                type="password"
                id="password"
                placeholder="dokładnie 8 cyfr"
                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900
                     placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            />
            <ErrorMessage name="password" class="text-red-500 text-xs mt-1" />
          </div>
          <div v-if="error" class="text-red-500 text-sm mb-4">
            {{ error }}
          </div>
          <div>
            <button
                type="button"
                @click="handleSubmit(onSubmit)"
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white
             hover:bg-indigo-500 transition"
            >
              Zaloguj się
            </button>
          </div>
        </Form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Form, Field, ErrorMessage, useForm } from 'vee-validate';
import * as Yup from 'yup';
import { useLogin } from '@/modules/login/composables/useLogin';

const { login, error } = useLogin();

const schema = Yup.object({
  login: Yup.string()
      .required('Login jest wymagany')
      .matches(
          /^[A-Z][a-z]+[A-Z][a-z]+$/,
          'Podaj imię i nazwisko, np. ImieNazwisko'
      ),

  password: Yup.string()
      .required('Hasło jest wymagane')
      .matches(
          /^\d{4}-\d{2}-\d{2}$/,
          'Format: RRRR-MM-DD'
      ),
});

const { handleSubmit: validateSubmit, setErrors } = useForm({
  validationSchema: schema,
  validateOnInput: true,
});

// Submit
const onSubmit = async (values: { login: string; password: string }) => {
    await login(values);

};
</script>