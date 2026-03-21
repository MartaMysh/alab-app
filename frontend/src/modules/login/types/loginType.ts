export interface LoginPayload {
    login: string;
    password: string;
}

export interface LoginResponse {
    token: string;
    token_type: string;
    expires_in: number;
}
