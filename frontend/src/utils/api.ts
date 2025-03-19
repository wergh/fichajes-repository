import axios from 'axios'

    const BASE_URL = 'https://draliatest.azurewebsites.net/api/availability'

// export const getWeeklySlots = async (date: string): Promise<WeeklySlotsResponse | []> => {
//     try {
//         const response = await axios.get<WeeklySlotsResponse>(`${BASE_URL}/GetWeeklySlots/${date}`)
//         if (Array.isArray(response.data)) {
//             return response.data
//         } else {
//             console.error('Invalid data format', response.data)
//             return []
//         }
//     } catch (error) {
//         console.error('Error fetching slots:', error)
//         return []
//     }
// }
//
// export const bookSlot = async (data: BookSlotData): Promise<BookResult> => {
//     try {
//         return await axios.post(`${BASE_URL}/BookSlot`, data);
//     } catch (error) {
//         console.error('Error booking slot:', error)
//     }
// }
