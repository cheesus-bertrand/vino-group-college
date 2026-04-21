<template>
  <div class="banniere">
    <h2 class="banniere-titre">Modifier vos données</h2>
  </div>
  <Navbar />
  <div class="container-plain">
    <!-- Formulaire de modification des données de l'usager -->
    <form @submit.prevent="updateUsager" class="bloc-form">
      <div>
        <label>Nom :</label>
        <input type="text" v-model="nom" placeholder="Votre nom" />
        <div v-if="erreurs.nom" class="erreur">
          {{ erreurs.nom[0] }}
        </div>
      </div>

      <div>
        <label>Courriel :</label>
        <span class="bloc-form-message"
          >Modifier le courriel entraîne une redirection vers la connexion</span
        >
        <input
          class="bloc-input-email"
          type="email"
          v-model="courriel"
          placeholder="exemple@email.com"
        />
        <div v-if="erreurs.courriel" class="erreur">
          {{ erreurs.courriel[0] }}
        </div>
      </div>
      <button type="submit" class="signup-btn">Modifier</button>
      <p v-if="erreur" class="erreur">{{ erreur }}</p>
    </form>
  </div>
</template>
<script>
import axios from "axios";
import Navbar from "../../components/Navbar.vue";
import api, { fetchCsrfToken } from "../../api";
import { useNotifStore } from '../../stores/notification';

export default {
  components: {
    Navbar,
  },
  data() {
    return {
      nom: "",
      ancien_courriel: "",
      courriel: "",
      mot_de_passe: "",
      erreurs: {},
      messageSucces: "",
      erreur: "",
    };
  },
  mounted() {
    this.getUsager();
  },
  methods: {
    /**
     * Récupère les informations d’un usager à partir de son ID
     * présent dans l’URL (route params).
     * Remplit les champs du formulaire (nom, courriel).
     */
    async getUsager() {
      try {
        const id = this.$route.params.id;
        const response = await axios.get(
          `http://localhost:8000/api/usagers/${id}`,
        );

        this.nom = response.data.data.nom;
        this.courriel = response.data.data.courriel;
        this.ancien_courriel = response.data.data.courriel;
      } catch (error) {
        console.error("Erreur getUsager:", error);
      }
    },

    /**
     * Met à jour les informations d’un usager.
     * Envoie les données modifiées au backend via une requête PUT.
     * Affiche un message de succès ou les erreurs de validation.
     */
    async updateUsager() {
      // Vider les erreurs avant la requête
      this.erreurs = {};
      this.messageSucces = "";
      try {
        // Récupérer le token CSRF avant d'envoyer la requête PUT
        await fetchCsrfToken();
        const id = this.$route.params.id;
        // Envoyer la requête PUT pour mettre à jour les données de l'usager
        const response = await api.put(`/usagers/${id}`, {
          nom: this.nom,
          courriel: this.courriel,
        });
        // Afficher un message de succès
        if (this.ancien_courriel !== this.courriel) {
          this.ancien_courriel = this.courriel;
          //ajout d'une notification pour le catalogue
          const notif = useNotifStore();
          notif.montreMessage('Vous avez été deconnecté avec succès!', 'bloc-modale-succes');
          this.$router.push("/connexion-usager");
        } else {
          //envoy une notification au catalogue, une fois qu'on y retourne
          const notif = useNotifStore();
          notif.montreMessage('Votre information(s) a été modifier avec succès!', 'bloc-modale-succes');
          // Rediriger vers la page de profil après la mise à jour
          this.$router.push("/profil-usager");
        }
      } catch (erreur) {
        if (erreur.response && erreur.response.status === 422) {
          this.erreurs = erreur.response.data.erreurs;
        }
        else if (erreur.response.status === 403) {
          this.erreur = "Vous n'avez pas la permission de modifier ce profil";
        }
        else if (erreur.response.status === 404) {
          this.erreur = "Utilisateur non trouvé";
        }
        else if (erreur.response.status === 401) {
          this.erreur= "Session expirée. Veuillez vous reconnecter";
          setTimeout(() => {
            this.$router.push("/connexion-usager");
          }, 2000);
        }
        else if (erreur.response.status === 500) {
          this.erreur = "Erreur serveur. Veuillez réessayer plus tard";
        }
        else {
          this.erreur = erreur.response.data.message || "Une erreur est survenue lors de la mise à jour";
        }
      }
    }
  },
};
</script>
<style scoped>
@media (min-width: 1024px) {
  .banniere {
    text-align: center;
    margin-bottom: 2rem;
  }

  .container-plain {
    max-width: 550px;
    margin: 4rem auto;
    padding: 0 1.5rem;
  }

  .bloc-form {
    width: 100%;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
  }

  .bloc-form input {
    width: 100%;
    box-sizing: border-box;
  }

  .signup-btn {
    width: 100%;
    max-width: 280px;
    margin: 2rem auto !important;
    display: block;
  }
}
</style>
