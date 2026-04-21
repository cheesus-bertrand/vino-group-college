<template>
  <!-- Page de détail d'une bouteille dans la liste d'achat, avec possibilité de supprimer ou modifier la bouteille -->
  <Navbar />
  <!-- Affichage de la carte de la bouteille si elle existe, sinon rien n'est affiché -->
  <div class="page-vinCarte">
    <VinCarte
      v-if="store.bouteilleVin"
      :bouteilleVin="store.bouteilleVin"
      @supprimer-bouteille="ouvrirModale"
      @modifier-bouteille="modifierBouteille"
    />

    <Review 
			v-if="store.bouteilleVin" 
			:initialRating="store.review?.rating"
      :initialComment="store.review?.comment" 
      @save="handleSaveReview"
		/>

    <!-- Affichage de la modale de confirmation pour la suppression de la bouteille -->
    <ModalConfirmation
      :show="afficherModale"
      message="Voulez-vous supprimer cette bouteille, la suppression est définitive ?"
      confirmText="Supprimer"
      cancelText="Annuler"
      @confirm="supprimerBouteille"
      @cancel="afficherModale = false"
    />
  </div>
</template>

<script>
import { useListeStore } from "../../stores/listeAchatBouteille.js";
import Navbar from "../../components/Navbar.vue";
import api from "../../api";
import VinCarte from "../../components/VinCarte.vue";
import Review from "../../components/Review.vue";
import ModalConfirmation from "../../components/ModalConfirmation.vue";

export default {
  components: {
    Navbar,
    VinCarte,
    ModalConfirmation,
    Review,
  },

  data() {
    return {
      afficherModale: false,
    };
  },

  computed: {
    // Accès au store de la liste d'achat pour récupérer les détails de la bouteille à afficher
    store() {
      return useListeStore();
    },
  },

  mounted() {
    // Récupère les détails de la bouteille à afficher en utilisant l'ID de la route
    this.store.fetchVin(this.$route.params.id);
  },

  methods: {
    // Ouvre la modale de confirmation pour la suppression de la bouteille
    ouvrirModale() {
      this.afficherModale = true;
    },
    // Supprime la bouteille de la liste d'achat en appelant l'API, puis redirige vers la page précédente
    async supprimerBouteille() {
      try {
        const bouteilleId = this.store.bouteilleVin.id;
        await api.delete(`/liste-achats/${bouteilleId}`);
        this.store.bouteilleVin = null;
        this.afficherModale = false;
        this.$router.back();
      } catch (erreur) {
        console.error("Erreur lors de la suppression de la bouteille:", erreur);
      }
    },
    // Redirige vers la page de modification de la bouteille en utilisant son SKU
    modifierBouteille() {
      this.$router.push(
        `/bouteille/ModifierBouteillePerso/${this.store.bouteilleVin.sku}`,
      );
    },
    async handleSaveReview(data) {
      try {
				await api.post('/reviews', {
					vin_id: this.store.bouteilleVin.vin_id,
          usager_id: this.store.bouteilleVin.usager_id,
					rating: data.rating,
					comment: data.comment
				});
				
				this.store.review = { ...data };
				
			} catch (error) {
				console.error(error);
			}
		},    
  },
};
</script>
