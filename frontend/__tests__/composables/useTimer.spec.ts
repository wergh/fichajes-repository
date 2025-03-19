import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'
import { useTimer } from '@/composables/useTimer'

describe('useTimer', () => {
  beforeEach(() => {
    // Mock setInterval
    vi.useFakeTimers()
  })

  afterEach(() => {
    vi.restoreAllMocks()
  })

  it('initializes with seconds at 0', () => {
    const { seconds } = useTimer()

    expect(seconds.value).toBe(0)
  })

  it('starts timer with initial seconds', () => {
    const { seconds, start } = useTimer()

    start(10)

    expect(seconds.value).toBe(10)
  })

  it('increments seconds when timer is running', () => {
    const { seconds, start } = useTimer()

    start()
    vi.advanceTimersByTime(3000) // Advance 3 seconds

    expect(seconds.value).toBe(3)
  })

  it('stops timer', () => {
    const { seconds, start, stop } = useTimer()

    start()
    vi.advanceTimersByTime(2000) // Advance 2 seconds
    stop()
    vi.advanceTimersByTime(3000) // Advance 3 more seconds

    expect(seconds.value).toBe(2) // Should still be 2, not 5
  })

  it('resets timer to 0', () => {
    const { seconds, start, reset } = useTimer()

    start(10)
    vi.advanceTimersByTime(2000) // Advance 2 seconds
    reset()

    expect(seconds.value).toBe(0)
  })

  it('updates isRunning state correctly', () => {
    const { isRunning, start, stop } = useTimer()

    expect(isRunning.value).toBe(false)

    start()
    expect(isRunning.value).toBe(true)

    stop()
    expect(isRunning.value).toBe(false)
  })
})
