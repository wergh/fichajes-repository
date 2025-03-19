import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import ActionButton from '@/components/ActionButton.vue'

describe('ActionButton.vue', () => {
  it('renders correctly with enter action', () => {
    const wrapper = mount(ActionButton, {
      props: {
        action: 'enter',
        loading: false
      }
    })

    expect(wrapper.text()).toContain('Entrar')
    expect(wrapper.classes()).toContain('bg-green-500')
  })

  it('renders correctly with exit action', () => {
    const wrapper = mount(ActionButton, {
      props: {
        action: 'exit',
        loading: false
      }
    })

    expect(wrapper.text()).toContain('Salir')
    expect(wrapper.classes()).toContain('bg-red-400')
  })

  it('shows loading spinner when loading is true', () => {
    const wrapper = mount(ActionButton, {
      props: {
        action: 'enter',
        loading: true
      }
    })

    expect(wrapper.find('.spinner-border').exists()).toBe(true)
  })

  it('emits action event when clicked', async () => {
    const wrapper = mount(ActionButton, {
      props: {
        action: 'enter',
        loading: false
      }
    })

    await wrapper.trigger('click')

    expect(wrapper.emitted()).toHaveProperty('action')
    expect(wrapper.emitted().action[0]).toEqual(['enter'])
  })

  it('is disabled when loading is true', () => {
    const wrapper = mount(ActionButton, {
      props: {
        action: 'enter',
        loading: true
      }
    })

    expect(wrapper.attributes('disabled')).toBeDefined()
  })
})
