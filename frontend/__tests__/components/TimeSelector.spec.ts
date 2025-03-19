import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import TimeSelector from '@/components/common/TimeSelector.vue'

describe('TimeSelector.vue', () => {
  it('renders correctly with default props', () => {
    const wrapper = mount(TimeSelector)

    expect(wrapper.find('select').exists()).toBe(true)
    expect(wrapper.findAll('select').length).toBe(2)
  })

  it('renders label when provided', () => {
    const wrapper = mount(TimeSelector, {
      props: {
        label: 'Test Label'
      }
    })

    expect(wrapper.text()).toContain('Test Label')
  })

  it('initializes with the provided modelValue', async () => {
    const wrapper = mount(TimeSelector, {
      props: {
        modelValue: '14:30'
      }
    })

    // Wait for the component to update after mounting
    await wrapper.vm.$nextTick()

    const hourSelect = wrapper.findAll('select')[0]
    const minuteSelect = wrapper.findAll('select')[1]

    expect(hourSelect.element.value).toBe('14')
    expect(minuteSelect.element.value).toBe('30')
  })

  it('emits update:modelValue when time changes', async () => {
    const wrapper = mount(TimeSelector)

    const hourSelect = wrapper.findAll('select')[0]
    await hourSelect.setValue('10')

    const minuteSelect = wrapper.findAll('select')[1]
    await minuteSelect.setValue('45')

    expect(wrapper.emitted()).toHaveProperty('update:modelValue')
    expect(wrapper.emitted()['update:modelValue'][1][0]).toBe('10:45')
  })

  it('updates when modelValue prop changes', async () => {
    const wrapper = mount(TimeSelector, {
      props: {
        modelValue: '09:15'
      }
    })

    await wrapper.setProps({ modelValue: '16:45' })
    await wrapper.vm.$nextTick()

    const hourSelect = wrapper.findAll('select')[0]
    const minuteSelect = wrapper.findAll('select')[1]

    expect(hourSelect.element.value).toBe('16')
    expect(minuteSelect.element.value).toBe('45')
  })
})
