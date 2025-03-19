import type {WorkEntry} from "@/interfaces/WorkEntry";

export function getAtomDates(entry: WorkEntry) {
    const [day, month, year] = entry.formattedDate.split(', ')[1].split('/');
    const baseDate = `${year}-${month}-${day}`;

    const startTime = entry.formattedStartTime.__v_isRef
        ? entry.formattedStartTime.value
        : entry.formattedStartTime;

    const endTime = entry.formattedEndTime.__v_isRef
        ? entry.formattedEndTime.value
        : entry.formattedEndTime;

    const startDateAtom = `${baseDate}T${startTime}:00+00:00`;
    const endDateAtom = `${baseDate}T${endTime}:00+00:00`;

    return { startDateAtom, endDateAtom };
}

export function formatDateForHumans(date: Date) {
    const options: Intl.DateTimeFormatOptions = {
        weekday: 'long',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    };
    return date.toLocaleDateString('es-ES', options)
}

export function formatHourForHumans(date: Date) {
    return date.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit'
    });
}
