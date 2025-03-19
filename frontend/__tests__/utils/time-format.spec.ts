import { describe, it, expect } from 'vitest'
import { getAtomDates, formatDateForHumans, formatHourForHumans } from '@/utils/time-format'
import type { WorkEntry } from '@/interfaces/WorkEntry'

describe('time-format utilities', () => {
  describe('getAtomDates', () => {
    it('converts dates to ATOM format correctly', () => {
      const mockEntry: WorkEntry = {
        id: '123',
        startDate: '2025-03-18 17:14:29',
        endDate: '2025-03-18 17:14:39',
        formattedDate: 'martes, 18/03/2025',
        formattedStartTime: '17:14',
        formattedEndTime: '17:30'
      }

      const result = getAtomDates(mockEntry)

      expect(result.startDateAtom).toBe('2025-03-18T17:14:00+00:00')
      expect(result.endDateAtom).toBe('2025-03-18T17:30:00+00:00')
    })

    it('handles ref values correctly', () => {
      const mockEntry: any = {
        id: '123',
        startDate: '2025-03-18 17:14:29',
        endDate: '2025-03-18 17:14:39',
        formattedDate: 'martes, 18/03/2025',
        formattedStartTime: {
          __v_isRef: true,
          value: '17:14'
        },
        formattedEndTime: {
          __v_isRef: true,
          value: '17:30'
        }
      }

      const result = getAtomDates(mockEntry)

      expect(result.startDateAtom).toBe('2025-03-18T17:14:00+00:00')
      expect(result.endDateAtom).toBe('2025-03-18T17:30:00+00:00')
    })
  })

  describe('formatDateForHumans', () => {
    it('formats date correctly for Spanish locale', () => {
      const date = new Date('2025-03-18T10:30:00')
      const result = formatDateForHumans(date)

      // This test might be locale-dependent
      expect(result).toMatch(/martes, 18\/03\/2025/)
    })
  })

  describe('formatHourForHumans', () => {
    it('formats hour correctly for Spanish locale', () => {
      const date = new Date('2025-03-18T14:30:00')
      const result = formatHourForHumans(date)

      // This test might be locale-dependent
      expect(result).toMatch(/14:30/)
    })
  })
})
