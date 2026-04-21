<template>
  <Navbar />

  <div class="banniere">
    <h1 class="banniere-titre">Cellier: {{ cellierNom }}</h1>
  </div>
  <!-- Affiche une notification -->
  <div v-if="notifStore.message" :class="['notif', notifStore.type]">
    {{ notifStore.message }}
  </div>

  <!-- Lien pour ajouter une bouteille personnalisée -->
  <div>
    <router-link
      class="cellier-lien-ajout-bouteille"
      :to="`/bouteille/AjouterBouteillePerso/${cellierId}`"
    >
      Ajouter une bouteille personnalisée
      <ChevronRightIcon class="cellier-icone-ajout-bouteille" />
    </router-link>
  </div>
  <!-- Cartes des vins du cellier -->
  <VinCellierCarte
    v-for="item in vins"
    :key="item.id"
    :vin="item.vin"
    :id="item.id"
    :quantite="item.quantite"
    @ouvrir-modale="ouvrirModale"
    @update-quantite="mettreAJourQuantite"
  />
  <!-- Modale de confirmation de suppression -->
  <ModalConfirmation
    :show="afficherModale"
    message="Voulez-vous supprimer ce vin de ce cellier ?"
    confirmText="Supprimer"
    cancelText="Annuler"
    @confirm="confirmerSuppression"
    @cancel="afficherModale = false"
  />
  <div class="espacement"></div>
</template>

<script setup>
import { ChevronRightIcon } from "@heroicons/vue/24/outline";
</script>

<script>
import api from "../../api";
import Navbar from "../../components/Navbar.vue";
import VinCellierCarte from "../../components/VinCellierCarte.vue";
import ModalConfirmation from "../../components/ModalConfirmation.vue";
import { useNotifStore } from '../../stores/notification';

export default {
  components: {
    Navbar,
    VinCellierCarte,
    ModalConfirmation,
  },
  data() {
    return {
      cellierNom: "",
      cellierId: "",
      vins: [],
      erreur: "",
      afficherModale: false,
      idASupprimer: null,
    };
  },
  methods: {
    // Gere l'affichage de toute les bouteilles (avec leur quantite), qui sont dans ce cellier
    async afficherDetailCellier() {
      try {
        // le backend retourne les infos du detail-cellier (incluant - le cellier, les vins(avec leur quantite))
        const id = this.$route.params.id;
        const reponse = await api.get(`/detail-cellier/${id}`);

        this.cellierNom = reponse.data.cellier.nom;

        // this.cellierId est utilisé pour l'ajout de bouteille personnalisée dans ce cellier
        this.cellierId = reponse.data.cellier.id;

        this.vins = reponse.data.vins;
      } catch (erreur) {
        this.erreur = "Erreur lors de l'affichage des details de ce cellier";
      }
    },
    //Ouvrire la modale de suppression de bouteille du cellier
    ouvrirModale(id) {
      this.idASupprimer = id;
      this.afficherModale = true;
    },
    // Une fois qui l'utilisateur confirme la suppression d'un bouteille du cellier
    async confirmerSuppression() {
      try {
        // supprimer grace a cette route dans le backend, qui supprime dans la DB
        await api.delete(`/cellier-vins/${this.idASupprimer}`);

        // Supprimer localement
        this.vins = this.vins.filter((item) => item.id !== this.idASupprimer);

        // enlever l'affichage du Modale de suppression
        this.afficherModale = false;
        this.idASupprimer = null;
      } catch (err) {
        this.erreur =
          "Erreur lors de la suppression d'une bouteille dans ce cellier";
      }
    },
    //Ajouter ou reduire la quantite des bouteilles
    mettreAJourQuantite({ id, quantite }) {
      const item = this.vins.find((v) => v.id === id);
      if (item) {
        item.quantite = quantite;
      }
    },
  },
  computed: {
    notifStore() {
      return useNotifStore();
    },
  },
  // Afficher les details du cellier (incluant les vins et leur quantite) une fois que la page est monté
  mounted() {
    this.afficherDetailCellier();
  },
};
</script>
