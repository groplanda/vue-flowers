<template lang="pug">
  transition(name="fade" appear)
    .popup
      ._dialog(:class="[className, popupFull]")
        ._content
          button(type="button" @click="onClose" v-if="showClose")._close
            icon(name="close" component="header")._close-ico
          slot
</template>
<script>

export default {
  name: "Popup",
  props: {
    showClose: {
      type: Boolean,
      default: true
    },
    className: {
      type: String,
      default: ''
    },
    fullscreen: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    popupFull() {
      return {
        'popup__dialog_full': this.fullscreen
      }
    }
  },
  methods: {
    onClose() {
      this.$emit('close');
    },
    getScrollBarWith() {
      const documentWidth = parseInt(document.documentElement.clientWidth);
      const windowsWidth = parseInt(window.innerWidth);
      return windowsWidth - documentWidth;
    },
    setOffset(elem, width) {
      elem.style.paddingRight = `${width}px`;
    }
  },
  created() {
    const width  = this.getScrollBarWith();
    document.body.classList.add('open-modal');
    this.setOffset(document.body, width);
  },
  destroyed() {
    setTimeout(() => {
    this.setOffset(document.body, 0);
    document.body.classList.remove('open-modal');
    }, 500)
  },
}
</script>