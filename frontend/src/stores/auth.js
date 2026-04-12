import { defineStore } from "pinia";
import api from "../api";

// Export pour pouvoir utiliser useAuthStore dans d'autre fichier Vue
export const useAuthStore = defineStore("auth", {

  // état réactif du authStore avec des propriete réactive (changeante)
  state: () => ({
    usager: null,
    loading: false,
  }),

  actions: {
    // Va chercher l'usager connecter (si il y en a un)
    async fetchUsager() {
      this.loading = true;
      try {
        const reponse = await api.get("/usagerConnecter");
        this.usager = reponse.data;
      } catch {
        this.usager = null;
      } finally {
        this.loading = false;
      }
    },
  },
});
