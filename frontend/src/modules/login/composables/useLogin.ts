import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { login as loginApi } from '@/api/';
import type { LoginPayload, LoginResponse } from '../types/loginType.ts';

export function useLogin() {
    const router = useRouter();

    const loading = ref(false);
    const error = ref<string | null>(null);

    const login = async (payload: LoginPayload) => {
        loading.value = true;
        error.value = null;

        try {
            const data: LoginResponse = await loginApi(payload);

            localStorage.setItem('jwt_token', data.token);
            const expiresAt = Date.now() + data.expires_in * 1000;
            localStorage.setItem('jwt_expires_at', String(expiresAt));

            router.push({ name: 'results' });
        } catch (e: any) {
            error.value = 'Nieprawidłowe dane logowania';
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        error,
        login,
    };
}
