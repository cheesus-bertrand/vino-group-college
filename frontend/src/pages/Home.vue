<template>
  <Navbar />
  <div class="home">
    <div class="banniere">
      <h2 class="banniere-titre">Catalogue des vins</h2>
    </div>

    <WineGrid v-if="!loading" :vins="vins" />

    <Pagination
      v-if="!loading && totalPages > 1"
      :page="page"
      :totalPages="totalPages"
      :perPage="perPage"
      @next="nextPage"
      @prev="prevPage"
      @changePerPage="changePerPage"
    />
    <div class="espacement"></div>
  </div>
</template>

<script>
import { useWineStore } from "../stores/wineStore";
import WineGrid from "../components/WineGrid.vue";
import Navbar from "../components/Navbar.vue";
import Pagination from "../components/Pagination.vue";

export default {
  components: {
    WineGrid,
    Navbar,
    Pagination,
  },

  data() {
    return {
      showFilter: false,
      page: 1,
      perPage: 12,
      selected: {
        countries: [],
        regions: [],
        cepages: [],
        prix: [],
        formats: [],
        degres: [],
        producteurs: [],
        millesimes: [],
        couleur: [],
      },
      wineStore: useWineStore(),
    };
  },

  computed: {
    vins() {
      return this.wineStore.wines;
    },

    loading() {
      return this.wineStore.loading;
    },

    totalPages() {
      return Math.ceil(this.wineStore.total / this.perPage);
    },
    countries() {
      return this.wineStore.filters.countries || [];
    },
    regions() {
      return this.wineStore.filters.regions || [];
    },
    cepages() {
      return this.wineStore.filters.cepages || [];
    },
    prix() {
      return this.wineStore.filters.prix || [];
    },
    formats() {
      return this.wineStore.filters.formats || [];
    },
    degres() {
      return this.wineStore.filters.degres || [];
    },
    millesimes() {
      return this.wineStore.filters.millesimes || [];
    },
  },

  watch: {
    selected: {
      handler() {
        this.page = 1;
        this.fetchWines();
      },
      deep: true,
    },
    page() {
      this.fetchWines();
    },
    perPage() {
      this.page = 1;
      this.fetchWines();
    },
  },

  methods: {
    formatAlcohol(value) {
      if (!value) return "-";
      const num = parseFloat(value);
      return isNaN(num) ? value : num.toFixed(2);
    },

    toggleFilter() {
      this.showFilter = !this.showFilter;
    },

    async fetchWines() {
      const filters = {};
      await this.wineStore.fetchAllWines(this.page, this.perPage, filters);
    },

    goToPage(p) {
      this.page = p;
    },

    nextPage() {
      if (this.page < this.totalPages) this.page++;
    },

    prevPage() {
      if (this.page > 1) this.page--;
    },

    changePerPage(val) {
      this.perPage = val;
    },
  },

  async mounted() {
    await this.fetchWines();
  },
};
</script>
