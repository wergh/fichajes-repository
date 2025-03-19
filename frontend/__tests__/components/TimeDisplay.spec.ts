import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import TimeDisplay from '@/components/TimeDisplay.vue'

describe('TimeDisplay.vue', () => {
  it('formats current time correctly with hours', () => {
    const wrapper = mount(TimeDisplay, {
      props: {
        currentTime: 3661, // 1h 1min 1s
        targetTime: 28800 // 8h
      }
    })

    expect(wrapper.text()).toContain('1h 01min 01s')
  })

  it('formats current time correctly without hours', () => {
    const wrapper = mount(TimeDisplay, {
      props: {
        currentTime: 61, // 1min 1s
        targetTime: 28800 // 8h
      }
    })

    expect(wrapper.text()).toContain('01min 01s')
  })

  it('formats target time correctly', () => {
    const wrapper = mount(TimeDisplay, {
      props: {
        currentTime: 0,
        targetTime: 28800 // 8h
      }
    })

    expect(wrapper.text()).toContain('8:00:00')
  })

  it('uses default target time if not provided', () => {
    const wrapper = mount(TimeDisplay, {
      props: {
        currentTime: 0
      }
    })

    expect(wrapper.text()).toContain('8:00:00')
  })
})
