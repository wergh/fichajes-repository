// user.service.ts
import { apiService } from './api.service';
import { endpoints } from '@/config/api';
import type {UserForm} from "@/utils/UserFormData";

export const userService = {
    async getUsers() {
        try {
            return await apiService.get(endpoints.users.getAll);
        } catch (error) {
            throw error;
        }
    },
    async getUserById(userId: string) {
        try {
            return await apiService.get(endpoints.users.getById(userId));
        } catch (error) {
            throw error;
        }
    },
    async createUser(formData: UserForm) {
        try {
            return await apiService.post(endpoints.users.create, formData);
        } catch (error) {
            throw error;
        }
    },
};
