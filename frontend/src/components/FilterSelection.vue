<template>
  <li>
    <!-- Le titre de la section de filtre, cliquable pour ouvrir/fermer -->
    <div class="filter-title" @click="toggle">
      <strong>{{ title }}</strong>
      <span>{{ open ? "−" : "+" }}</span>
    </div>
    <!-- Le contenu du filtre, affiché uniquement si "open" est vrai -->
    <div v-show="open">
      <div v-if="clearable" class="clear-filter">
        <label>
          <input
            type="checkbox"
            :checked="modelValue.length === 0"
            @change="clearFilter"
          />
          Tout désélectionner
        </label>
      </div>

      <!-- Affichage de chaque élément de filtre avec une checkbox -->
      <div v-for="item in items" :key="item" class="filter-item">
        <label>
          <input
            type="checkbox"
            :value="item"
            :checked="modelValue.includes(item)"
            @change="onChange(item, $event)"
          />
          {{ format(item) }}
        </label>
      </div>
    </div>
  </li>
</template>

<script>
export default {
  props: {
    title: String,
    items: Array,
    modelValue: Array,
    formatter: Function,
    clearable: { type: Boolean, default: false },
  },
  emits: ["update:modelValue", "clear"],
  data() {
    return {
      open: false,
    };
  },
  methods: {
    // Fonction pour désélectionner tous les éléments du filtre
    clearFilter() {
      this.$emit("update:modelValue", []);
      this.$emit("clear");
    },
    // Fonction pour basculer l'état d'ouverture du filtre
    toggle() {
      this.open = !this.open;
    },
    // Fonction pour gérer les changements de sélection des éléments du filtre
    onChange(item, event) {
      let newValue = [...this.modelValue];

      // Si la checkbox est cochée, ajouter l'item à la nouvelle valeur, sinon le retirer
      if (event.target.checked) {
        newValue.push(item);
      } else {
        newValue = newValue.filter((i) => i !== item);
      }

      // Émettre l'événement de mise à jour avec la nouvelle valeur
      this.$emit("update:modelValue", newValue);
    },
    // Fonction pour formater l'affichage de chaque élément du filtre
    format(item) {
      return this.formatter ? this.formatter(item) : item;
    },
  },
};
</script>
