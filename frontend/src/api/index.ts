import apiClient from './client';
import type {LoginPayload, LoginResponse} from "../modules/login/types/loginType.ts";
import type {ResultsResponse} from "../modules/results/types/resultType.ts";

export const login = async (payload: LoginPayload): Promise<LoginResponse> => {
    const { data } = await apiClient.post<LoginResponse>('/login', payload);
    return data;
};

export const getResults = async (): Promise<ResultsResponse> => {
    const { data } = await apiClient.get<ResultsResponse>('/results');
    return data;
};
