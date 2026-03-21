import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { getResults } from '@/api';
import type { ResultsResponse } from '../types/resultType.ts';

export function useResults() {
    const router = useRouter();

    const data = ref<ResultsResponse>({
        patient: { id: 0, name: '', surname: '', sex: '', birthDate: '' },
        orders: []
    });

    const loading = ref(false);
    const error = ref<string | null>(null);

    const checkTokenExpiration = () => {
        const expiresAt = Number(localStorage.getItem('jwt_expires_at') || 0);
        if (expiresAt && Date.now() > expiresAt) {
            logout();
        }
    };

    const fetchResults = async () => {
        loading.value = true;
        error.value = null;

        try {
            const res = await getResults();
            console.log(res);
            data.value = res;
        } catch (e: any) {
            if (e.response?.status === 401) {
                logout();
            } else if (e.response?.status === 404) {
                error.value = 'Brak danych';
            } else {
                error.value = 'Błąd podczas pobierania danych';
            }
        } finally {
            loading.value = false;
        }
    };

    const logout = () => {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('jwt_expires_at');
        router.push({ name: 'login' });
    };

    return {
        data,
        loading,
        error,
        fetchResults,
        checkTokenExpiration,
        logout,
    };
}
