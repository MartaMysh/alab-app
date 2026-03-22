<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-100 px-6">
    <div class="w-full max-w-sm">
      <h2 class="mb-6 text-center text-2xl font-bold text-gray-900">
        Zaloguj się do swojego konta
      </h2>

      <div class="bg-white shadow-lg rounded-xl p-6">
        <Form
            :validation-schema="schema"
            class="space-y-6"
            @submit="onSubmit"
        >
          <div>
            <label for="login" class="block text-sm font-medium text-gray-700">
              Login
            </label>
            <Field
                name="login"
                type="text"
                id="login"
                placeholder="ImieNazwisko"
                class="block w-full rounded-md border border-gray-300 px-3 py-2"
            />
            <ErrorMessage name="login" class="text-red-500 text-xs mt-1" />
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Hasło
            </label>
            <Field
                name="password"
                type="password"
                id="password"
                placeholder="RRRR-MM-DD"
                class="block w-full rounded-md border border-gray-300 px-3 py-2"
            />
            <ErrorMessage name="password" class="text-red-500 text-xs mt-1" />
          </div>

          <div v-if="error" class="text-red-500 text-sm mb-4">
            {{ error }}
          </div>

          <div>
            <button
                type="submit"
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white"
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
import { Form, Field, ErrorMessage, useForm, SubmissionHandler } from 'vee-validate'
import * as Yup from 'yup'
import { useLogin } from '@/modules/login/composables/useLogin'
import type {LoginPayload} from "@/modules/login/types/loginType.ts";

const { login, error } = useLogin()

const schema = Yup.object({
  login: Yup.string()
      .required('Login jest wymagany')
      .matches(/^[A-Z][a-z]+[A-Z][a-z]+$/, 'Podaj imię i nazwisko, np. ImieNazwisko'),

  password: Yup.string()
      .required('Hasło jest wymagane')
      .matches(/^\d{4}-\d{2}-\d{2}$/, 'Format: RRRR-MM-DD'),
})

useForm<LoginPayload>({
  validationSchema: schema,
})

const onSubmit: SubmissionHandler<LoginPayload> = async (values) => {
  await login(values)
}
</script>
