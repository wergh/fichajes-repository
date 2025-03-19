import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import EmptyState from '@/components/EmptyState.vue'

describe('EmptyState.vue', () => {
  it('renders default message', () => {
    const wrapper = mount(EmptyState)

    expect(wrapper.text()).toContain('Aún no tienes fichajes registrados')
  })

  it('renders custom message when provided', () => {
    const customMessage = 'Mensaje personalizado'
    const wrapper = mount(EmptyState, {
      props: {
        message: customMessage
      }
    })

    expect(wrapper.text()).toContain(customMessage)
  })

  it('renders the icon', () => {
    const wrapper = mount(EmptyState)

    expect(wrapper.find('svg').exists()).toBe(true)
  })
})
