<template>
  <!-- Filtre par couleur -->
  <li>
    <div class="filter-title" @click="toggle">
      <strong>Couleur</strong>
      <span>{{ open ? "−" : "+" }}</span>
    </div>
    <!-- etat d'ouverture du filtre -->
    <div v-show="open" class="color-filter">
      <span
        class="color-dot reset"
        :class="{ active: modelValue.length === 0 }"
        @click="clear"
        title="Tout"
      >
        ✕
      </span>
      <!-- Palette de couleurs des vins -->
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
  // Émet un événement pour mettre à jour la valeur du modèle
  emits: ["update:modelValue"],

  data() {
    return {
      // État d'ouverture du filtre
      open: true,
      // Palette de couleurs des vins avec leurs codes hexadécimaux et étiquettes
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
    // Permet de basculer l'état d'ouverture du filtre
    toggle() {
      this.open = !this.open;
    },
    // Permet de basculer la sélection d'une couleur dans le filtre
    toggleColor(value) {
      let newValue = [...this.modelValue];

      // Si la couleur est déjà sélectionnée, on la retire, sinon on l'ajoute
      if (newValue.includes(value)) {
        newValue = newValue.filter((v) => v !== value);
      } else {
        newValue.push(value);
      }

      // Émet un événement pour mettre à jour la valeur du modèle avec la nouvelle sélection de couleurs
      this.$emit("update:modelValue", newValue);
    },

    // Permet de réinitialiser la sélection de couleurs en émettant un événement avec une valeur vide
    clear() {
      this.$emit("update:modelValue", []);
    },
  },
};
</script>
