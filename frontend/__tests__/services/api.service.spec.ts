import { describe, it, expect, vi, beforeEach } from 'vitest'
import { apiService } from '@/services/api.service'
import { apiClient } from '@/config/api'

// Mock the apiClient
vi.mock('@/config/api', () => ({
  apiClient: {
    get: vi.fn(),
    post: vi.fn(),
    patch: vi.fn(),
    delete: vi.fn()
  }
}))

describe('api.service', () => {
  beforeEach(() => {
    vi.resetAllMocks()
  })

  describe('get', () => {
    it('calls apiClient.get with correct parameters', async () => {
      const mockResponse = { data: { id: 1, name: 'Test' } }
      vi.mocked(apiClient.get).mockResolvedValue(mockResponse)

      const result = await apiService.get('/test', { param: 'value' })

      expect(apiClient.get).toHaveBeenCalledWith('/test', { params: { param: 'value' } })
      expect(result).toEqual(mockResponse.data)
    })

    it('throws error when apiClient.get fails', async () => {
      const mockError = new Error('Network error')
      vi.mocked(apiClient.get).mockRejectedValue(mockError)

      await expect(apiService.get('/test')).rejects.toThrow('Network error')
    })
  })

  describe('post', () => {
    it('calls apiClient.post with correct parameters', async () => {
      const mockResponse = { data: { id: 1, name: 'Test' } }
      vi.mocked(apiClient.post).mockResolvedValue(mockResponse)

      const postData = { name: 'Test' }
      const result = await apiService.post('/test', postData)

      expect(apiClient.post).toHaveBeenCalledWith('/test', postData)
      expect(result).toEqual(mockResponse.data)
    })
  })

  describe('patch', () => {
    it('calls apiClient.patch with correct parameters', async () => {
      const mockResponse = { data: { id: 1, name: 'Updated' } }
      vi.mocked(apiClient.patch).mockResolvedValue(mockResponse)

      const patchData = { name: 'Updated' }
      const result = await apiService.patch('/test/1', patchData)

      expect(apiClient.patch).toHaveBeenCalledWith('/test/1', patchData)
      expect(result).toEqual(mockResponse.data)
    })
  })

  describe('delete', () => {
    it('calls apiClient.delete with correct parameters', async () => {
      const mockResponse = { data: { success: true } }
      vi.mocked(apiClient.delete).mockResolvedValue(mockResponse)

      const result = await apiService.delete('/test/1')

      expect(apiClient.delete).toHaveBeenCalledWith('/test/1')
      expect(result).toEqual(mockResponse.data)
    })
  })
})
