<template>
  <div class="container">
    <div class="bloc-img">
      <img src="../../assets/img/image.png" />
      <div class="bloc-img-secondaire">Vino</div>
    </div>
    <form @submit.prevent="updateUsager" class="bloc-form">
      <h2>Compte à modifier</h2>
      <p class="already-txt">
        Retourner ?
        <router-link to="/connexion-usager"> Se Connecter </router-link>
      </p>
      <!-- Nom -->
      <div>
        <label>Nom :</label>
        <input type="text" v-model="nom" placeholder="Votre nom" />
        <div v-if="erreurs.nom" class="erreur">
          {{ erreurs.nom[0] }}
        </div>
      </div>

      <div>
        <label>Courriel :</label>
        <input
          type="email"
          v-model="courriel"
          placeholder="exemple@email.com"
        />
        <div v-if="erreurs.courriel" class="erreur">
          {{ erreurs.courriel[0] }}
        </div>
      </div>

      <div>
        <label>Mot de passe :</label>
        <input
          type="password"
          v-model="mot_de_passe"
          placeholder="6 caractères au minimum"
        />
        <div v-if="erreurs.mot_de_passe" class="erreur">
          {{ erreurs.mot_de_passe[0] }}
        </div>
      </div>

      <button type="submit" class="signup-btn">Modifier votre compte</button>
    </form>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      nom: "",
      courriel: "",
      mot_de_passe: "",
      erreurs: {},
    };
  },
  mounted() {
    this.getUsager();
  },
  methods: {
    async getUsager() {
      try {
        const id = this.$route.params.id;

        const response = await axios.get(
          `http://localhost:8000/api/usagers/${id}`
        );

        console.log(response.data);

        this.nom = response.data.data.nom;
        this.courriel = response.data.data.courriel;
      } catch (error) {
        console.error("Erreur getUsager:", error);
      }
    },
    async updateUsager() {
      try {
        const id = this.$route.params.id; // récupère l'id dans l'URL

        const response = await axios.put(
          `http://localhost:8000/api/usagers/${id}`,
          {
            nom: this.nom,
            courriel: this.courriel,
            mot_de_passe: this.mot_de_passe,
          }
        );

        console.log(response.data);

        alert("Compte mis à jour !"); // temporaire
      } catch (erreur) {
        if (erreur.response && erreur.response.status === 422) {
          this.erreurs = erreur.response.data.erreurs;
        }
      }
    },
  },
};
</script>
