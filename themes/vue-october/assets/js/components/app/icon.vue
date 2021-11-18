<template>
  <svg :class="`icon-` + name + ` ` + addedClass" :viewBox="viewBox" />
</template>

<script>
import { parse } from "postsvg";
import render from "posthtml-render";

const cache = new Map();

export default {
  name: "icon",
  props: {
    name: {
      type: String,
      required: true
    },
    component: {
      type: String,
      required: true
    },
    addedClass: {
      type: String,
      default: ""
    },
    generateReadyEvent: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    svgString: ""
  }),
  computed: {
    filepath() {
      const path = require(`@svg/${this.component}/${this.name}.svg`).default;
      return '/themes/' + process.env.MIX_THEME + '/assets/' + path;

    },
    parsedSVG() {
      return this.svgString ? parse(this.svgString) : null;
    },
    viewBox() {
      return this.parsedSVG ? this.parsedSVG.root.attrs.viewBox : "0 0 20 20";
    }
  },
  watch: {
    filepath: {
      immediate: true,
      handler: "loadFile"
    },
    svgString: "refreshSvg",
    stroke: "refreshSvg",
    fill: "refreshSvg"
  },
  methods: {
    loadFile() {
      this.getSvgIconText()
        .then(responseText => (this.svgString = responseText))
        .catch(error => {
          console.error("Ошибка загрузки SVG-файла", error);
        });
    },

    async getSvgIconText() {
      let url = this.filepath;
      if (!cache.has(url)) {
        try {
          cache.set(
            url,
            fetch(url).then(r => r.text())
          );
        } catch (e) {
          cache.delete(url);
        }
      }
      return cache.has(url)
        ? await cache.get(url)
        : Promise.reject(new Error("Нет SVG-файла в локальном кэше"));
    },

    refreshSvg() {
      Promise.resolve(this.parsedSVG)
        .then(svgTree => {
          return svgTree;
        })
        .then(svgTree => render(svgTree.root.content))
        .then(svgHtml => (this.$el.innerHTML = svgHtml))
        .then(() => this.generateReadyEvent && this.$emit("ready"))
        .catch(error => {
          console.error("Ошибка при обновлении SVG", error);
          this.$emit("error", error);
        });
    }
  }
};
</script>

