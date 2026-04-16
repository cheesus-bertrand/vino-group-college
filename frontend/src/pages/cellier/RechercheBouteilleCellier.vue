<template>
  <Navbar />

  <div class="banniere">
    <h1 class="banniere-titre">Recherche bouteille dans les celliers</h1>
  </div>

  <div class="search-container">
    <Search class="search-icon" />
    <input
      v-model="search"
      type="text"
      placeholder="Rechercher une bouteille..."
      class="search-input"
    />
  </div>

  <div class="btn-recherche">
    <button class="btn btn-entete-cellier" @click="toggleFilter">
      <ListFilter class="icon" /><span>Filtrer </span>
    </button>
    <button class="btn btn-entete-cellier" @click="showTri = true">
      <ArrowDownNarrowWide class="icon" /><span>Trier </span>
    </button>
  </div>

  <div
    class="filtre-ouvrir"
    :class="{ active: showFilter }"
    @click="toggleFilter"
  ></div>

  <aside class="filter-panel" :class="{ active: showFilter }">
    <div class="filter-header">
      <h2>Filtres</h2>
    </div>

    <ul class="filter-list">
      <ColorFilter v-model="selected.couleur" />

      <FilterSection
        title="Pays"
        :items="filters.countries"
        v-model="selected.countries"
        clearable
      />

      <FilterSection
        title="Régions"
        :items="filters.regions"
        v-model="selected.regions"
        clearable
      />

      <FilterSection
        title="Cépages"
        :items="filters.cepages"
        v-model="selected.cepages"
        clearable
      />

      <FilterSection
        title="Prix ($)"
        :items="filters.prix"
        v-model="selected.prix"
        clearable
      />

      <FilterSection
        title="Format (ml)"
        :items="filters.formats"
        v-model="selected.formats"
        clearable
      />

      <FilterSection
        title="Degré (%)"
        :items="filters.degres"
        v-model="selected.degres"
        clearable
      />

      <FilterSection
        title="Millésime"
        :items="filters.millesimes"
        v-model="selected.millesimes"
        clearable
      />
    </ul>
  </aside>

  <ModalTri
    :show="showTri"
    :tri="tri"
    @apply="appliquerTri"
    @close="showTri = false"
  />

  <div class="liste-bouteilles">
    <div
      v-for="bouteille in bouteilles"
      :key="bouteille.id"
      class="carte-bouteille"
    >
      <img :src="bouteille.vin.image_url" alt="vin" class="image-vin" />

      <div class="info">
        <h3>{{ bouteille.vin.nom }}</h3>
        <p>Cellier : {{ bouteille.cellier.nom }}</p>
        <p>Quantité : {{ bouteille.quantite }}</p>
        <p>Prix : {{ bouteille.vin.prix }} $</p>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "../../components/Navbar.vue";
import { Search, ListFilter, ArrowDownNarrowWide } from "lucide-vue-next";
import FilterSection from "../../components/FilterSelection.vue";
import ColorFilter from "../../components/ColorFilter.vue";
import axios from "axios";
import ModalTri from "../../components/ModalTri.vue";

export default {
  components: {
    Navbar,
    Search,
    ListFilter,
    ArrowDownNarrowWide,
    FilterSection,
    ColorFilter,
    ModalTri,
  },

  data() {
    return {
      showTri: false,
      tri: 0,
      /**
       * Texte saisi par l'usager dans la barre de recherche
       * pour filtrer
       */
      search: "",
      /**
       * Liste des bouteilles récupérées
       */
      bouteilles: [],

      //Affichage du panneau des filtres
      showFilter: false,

      /**
       * Contient toutes les options de filtres disponibles
       * fourni par le backend
       */
      selected: {
        countries: [],
        regions: [],
        cepages: [],
        prix: [],
        formats: [],
        degres: [],
        millesimes: [],
        couleur: [],
      },

      filters: {
        countries: [],
        regions: [],
        cepages: [],
        prix: [],
        formats: [],
        degres: [],
        millesimes: [],
        couleur: [],
      },
    };
  },

  watch: {
    /**
     * Déclenché a chaque modification du champ de recherche
     * recharge automatiquement les bouteilles
     */
    search() {
      this.fetchBouteilles();
    },

    /**
     * Permet de détecter les filtres sélectionnés
     */
    selected: {
      handler() {
        this.fetchBouteilles();
      },
      deep: true,
    },
  },

  methods: {
    /**
     * Permet d'ouvrir ou fermer le panneau de filtres
     * en inversant la valeur du booléan showFilter
     */
    toggleFilter() {
      this.showFilter = !this.showFilter;
    },

    // Permet d'ouvrir ou fermer le panneau de tri
    toggleTri() {
      this.showTri = !this.showTri;
    },

    // Met à jour la valeur du tri, appelle la fonction et cache la fenêtre
    appliquerTri(triChoisi) {
      this.tri = triChoisi;
      this.fetchBouteilles();
      this.showTri = false;
    },

    /**
     * Méthode qui permet de chercher les bouteilles
     * depuis l'API du backend pour faire la recherche
     * et des filtres s.lectionnés
     */
    async fetchBouteilles() {
      try {
        //Envois d'une requête Get vers l'API Laravel
        const res = await axios.get("/api/bouteilles", {
          params: {
            //Terme de recherche entré par l'usager
            recherche: this.search,

            //Filtres sélectionnés par l'usager
            filters: this.selected,
            // Tri séléctionné par l'usager
            tri: this.tri,
          },
        });

        //Mise a jour de la liste des bouteilles affichées
        this.bouteilles = res.data.data || res.data;

        //Mise a jour des filtres disponibles dynamiquement
        //envoyés par le backend
        if (res.data.filters) {
          this.filters = res.data.filters;
        }
      } catch (error) {
        //Gestion des erreurs
        console.error("Erreur chargement bouteilles :", error);
      }
    },
  },

  mounted() {
    /**
     * Appelé automatiquement quand le composant est chargé
     * Elle permet d'afficher les bouteilles dès l'ouverture de la page
     */
    this.fetchBouteilles();
  },
};
</script>
