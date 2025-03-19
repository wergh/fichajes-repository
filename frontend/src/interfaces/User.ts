import type {WorkEntry} from "@/interfaces/WorkEntry";

export interface UserList {
    id: string
    name: string
}

export interface User {
    id: string
    name: string
    workEntries: WorkEntry[]
    totalTime: string
}

