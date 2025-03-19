import { describe, it, expect, vi, beforeEach } from 'vitest'
import { workEntryService } from '@/services/workEntry.service'
import { apiService } from '@/services/api.service'
import { endpoints } from '@/config/api'

// Mock the apiService
vi.mock('@/services/api.service', () => ({
  apiService: {
    get: vi.fn(),
    post: vi.fn(),
    patch: vi.fn(),
    delete: vi.fn()
  }
}))

describe('workEntry.service', () => {
  beforeEach(() => {
    vi.resetAllMocks()
  })

  describe('beginWorkEntry', () => {
    it('calls apiService.post with correct endpoint and data', async () => {
      const mockResponse = { data: { id: '1' } }
      vi.mocked(apiService.post).mockResolvedValue(mockResponse)

      const formData = { userId: '1' }
      const result = await workEntryService.beginWorkEntry(formData)

      expect(apiService.post).toHaveBeenCalledWith(endpoints.workEntries.create, formData)
      expect(result).toEqual(mockResponse)
    })
  })

  describe('closeWorkEntry', () => {
    it('calls apiService.get with correct endpoint', async () => {
      const mockResponse = { data: { id: '1' } }
      vi.mocked(apiService.get).mockResolvedValue(mockResponse)

      const result = await workEntryService.closeWorkEntry('1')

      expect(apiService.get).toHaveBeenCalledWith(endpoints.workEntries.close('1'))
      expect(result).toEqual(mockResponse)
    })
  })

  describe('deleteWorkEntry', () => {
    it('calls apiService.delete with correct endpoint', async () => {
      const mockResponse = { message: 'Deleted' }
      vi.mocked(apiService.delete).mockResolvedValue(mockResponse)

      const result = await workEntryService.deleteWorkEntry('1', '2')

      expect(apiService.delete).toHaveBeenCalledWith(endpoints.workEntries.delete('1', '2'))
      expect(result).toEqual(mockResponse)
    })
  })

  describe('updateWorkEntry', () => {
    it('calls apiService.patch with correct endpoint and data', async () => {
      const mockResponse = { data: { id: '1' } }
      vi.mocked(apiService.patch).mockResolvedValue(mockResponse)

      const formData = { userId: '1', startDate: '2023-01-01T09:00:00+00:00', endDate: '2023-01-01T17:00:00+00:00' }
      const result = await workEntryService.updateWorkEntry('1', formData)

      expect(apiService.patch).toHaveBeenCalledWith(endpoints.workEntries.update('1'), formData)
      expect(result).toEqual(mockResponse)
    })
  })
})
