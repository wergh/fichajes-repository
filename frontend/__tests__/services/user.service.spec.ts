import { describe, it, expect, vi, beforeEach } from 'vitest'
import { userService } from '@/services/user.service'
import { apiService } from '@/services/api.service'
import { endpoints } from '@/config/api'

// Mock the apiService
vi.mock('@/services/api.service', () => ({
  apiService: {
    get: vi.fn(),
    post: vi.fn()
  }
}))

describe('user.service', () => {
  beforeEach(() => {
    vi.resetAllMocks()
  })

  describe('getUsers', () => {
    it('calls apiService.get with correct endpoint', async () => {
      const mockUsers = [{ id: '1', name: 'John' }]
      vi.mocked(apiService.get).mockResolvedValue(mockUsers)

      const result = await userService.getUsers()

      expect(apiService.get).toHaveBeenCalledWith(endpoints.users.getAll)
      expect(result).toEqual(mockUsers)
    })

    it('throws error when apiService.get fails', async () => {
      const mockError = new Error('API error')
      vi.mocked(apiService.get).mockRejectedValue(mockError)

      await expect(userService.getUsers()).rejects.toThrow('API error')
    })
  })

  describe('getUserById', () => {
    it('calls apiService.get with correct endpoint and userId', async () => {
      const mockUser = { id: '1', name: 'John' }
      vi.mocked(apiService.get).mockResolvedValue(mockUser)

      const result = await userService.getUserById('1')

      expect(apiService.get).toHaveBeenCalledWith(endpoints.users.getById('1'))
      expect(result).toEqual(mockUser)
    })
  })

  describe('createUser', () => {
    it('calls apiService.post with correct endpoint and data', async () => {
      const mockUser = { id: '1', name: 'John' }
      vi.mocked(apiService.post).mockResolvedValue(mockUser)

      const userData = { name: 'John' }
      const result = await userService.createUser(userData)

      expect(apiService.post).toHaveBeenCalledWith(endpoints.users.create, userData)
      expect(result).toEqual(mockUser)
    })
  })
})
