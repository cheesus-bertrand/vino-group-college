<template>
  <li>
    <div class="filter-title" @click="toggle">
      <strong>Couleur</strong>
      <span>{{ open ? "−" : "+" }}</span>
    </div>

    <div v-show="open" class="color-filter">
      <span
        class="color-dot reset"
        :class="{ active: modelValue.length === 0 }"
        @click="clear"
        title="Tout"
      >
        ✕
      </span>

      <span
        v-for="color in colors"
        :key="color.value"
        class="color-dot"
        :class="{ active: modelValue.includes(color.value) }"
        :style="{ backgroundColor: color.hex }"
        @click="toggleColor(color.value)"
        :title="color.label"
      ></span>
    </div>
  </li>
</template>

<script>
export default {
  props: {
    modelValue: Array,
  },
  emits: ["update:modelValue"],

  data() {
    return {
      open: true,
      colors: [
        { value: "Rouge", hex: "#8b0000", label: "Rouge" },
        { value: "Blanc", hex: "#f1c40f", label: "Blanc" },
        { value: "Rosé", hex: "#ff6b81", label: "Rosé" },
        { value: "Orange", hex: "#e67e22", label: "Orange" },
        { value: "Brun(e)", hex: "#3B2414", label: "Brun" },
        { value: "Blanche", hex: "#FFF8E7", label: "Blanche" },
        { value: "Ambré(e)", hex: "#C68642", label: "Ambre" },
        { value: "Roux, rousse", hex: "#8B3A1E", label: "Roux, rousse" },
      ],
    };
  },

  methods: {
    toggle() {
      this.open = !this.open;
    },

    toggleColor(value) {
      let newValue = [...this.modelValue];

      if (newValue.includes(value)) {
        newValue = newValue.filter((v) => v !== value);
      } else {
        newValue.push(value);
      }

      this.$emit("update:modelValue", newValue);
    },

    clear() {
      this.$emit("update:modelValue", []);
    },
  },
};
</script>
