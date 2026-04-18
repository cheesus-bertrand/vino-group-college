<template>
  <div class="nom-cellier">
    <div class="vin-cellier-carte">
      <img :src="vin.image_url" :alt="vin.nom" class="cellier-img" />
      <div>
        <h2 class="nom">{{ vin.nom }}</h2>
      </div>
    </div>

    <div class="bouton-cellier">
      <button class="btn btn-cellier" @click="$emit('ouvrir-modale', id)">
        <Trash class="icons" />
      </button>

      <button class="btn btn-cellier" @click="voirDetail">
        <Eye class="icons" />
      </button>
    </div>
  </div>
</template>

<script>
import { Trash, PencilLine, Eye } from "lucide-vue-next";
import api, { fetchCsrfToken } from "../api";

export default {
  components: {
    Trash,
    PencilLine,
    Eye,
  },
  props: {
    vin: Object,
    id: Number,
  },
  data() {
    return {
      erreur: "",
    };
  },
  methods: {
    voirDetail() {
      this.$router.push(`/cellier-vin/${this.id}`);
    },
    // Envoie de requete pour modifier le nombre des bouteilles
    async modifierQuantiteVin(nouvelleQuantite) {
      if (nouvelleQuantite < 1) return;

      try {
        await fetchCsrfToken();
        await api.put(`/modifier-quantite/${this.id}`, {
          quantite: nouvelleQuantite,
        });
      } catch (erreur) {
        console.error(erreur);
      }
    },
  },
};
</script>
