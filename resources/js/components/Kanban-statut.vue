<template>
    <div class="kanban-container">
  
      <!-- Générer dynamiquement les colonnes pour chaque statut -->
      <div v-for="(status, key) in statuses" :key="key" class="kanban-column" :data-status="key">
        <h3 :class="status.class">
          {{ status.text }}
          <br />
          <span class="kanban-column-total">⏳ Total: {{ getTotalTimeForStatus(key) }} h</span>
        </h3>
        
        <draggable 
          v-model="statusDrgs[key]" 
          group="drgs" 
          itemKey="id"
          class="kanban-list vuedraggable-fade-move"
          @end="onDrop($event, key)"
        >
          <template #item="{ element }">
            <div class="kanban-item">
              <!-- ✅ Colonne gauche : Image + Nom + Bouton -->
              <div class="kanban-item-left">
                <img 
                  :src="`/images/${element.drg_name}.png`" 
                  alt="DRG Image" 
                  class="kanban-item-image"
                />
                <p class="kanban-item-title">{{ element.drg_name }}</p>
                <a :href="getDrgLink(element)" class="btn btn-info btn-sm kanban-item-btn">🔍 Voir</a>
              </div>
  
              <!-- ✅ Colonne droite : Infos + Statut -->
              <div class="kanban-item-right">
                <p class="kanban-item-meta">{{ element.material }}</p>
                <p class="kanban-item-meta">{{ element.thickness }} mm</p>
                <p class="kanban-item-meta">{{ element.sheet_qty }}</p>
                <p class="kanban-item-meta">{{ element.sheet_qty_done }}</p>
                <p class="kanban-item-meta">{{ getTotalTime(element) }} h</p>
                <p class="kanban-item-meta">{{ getRemainingTime(element) }} h</p>
              </div>
            </div>
          </template>
        </draggable>
      </div>
  
    </div>
  </template>
  <script>
import { ref, onMounted } from "vue";
import draggable from "vuedraggable";
import axios from "axios";

export default {
  components: { draggable },
  setup() {
    const drgs = ref([]); // Tous les DRGs
    const statusDrgs = ref({}); // DRGs classés par statut

    // Statuts disponibles
    const statuses = ref({
      1: { text: "A planifier", class: "badge badge-warning" },
      2: { text: "Planifier", class: "badge badge-primary" },
      3: { text: "En cours", class: "badge badge-info" },
      4: { text: "A refaire", class: "badge badge-danger" },
      5: { text: "Terminer", class: "badge badge-success" },
      6: { text: "Supprimer", class: "badge badge-secondary" },
      7: { text: "Stopper", class: "badge badge-dark" }
    });

    // Charger les DRGs et les classer par statut
    const fetchDrgs = async () => {
      try {
        const response = await axios.get("/api/kanban-drgs");
        drgs.value = response.data;

        // Initialisation des colonnes
        statusDrgs.value = Object.keys(statuses.value).reduce((acc, key) => {
          acc[key] = drgs.value.filter(drg => drg.statu == key);
          return acc;
        }, {});

      } catch (error) {
        console.error("Erreur lors du chargement des DRGs :", error);
      }
    };

    // Calcul du temps total pour chaque colonne
    const getTotalTimeForStatus = (status) => {
      if (!statusDrgs.value[status]) return "0.00";
      return statusDrgs.value[status].reduce((total, drg) => total + parseFloat(getTotalTime(drg) || 0), 0).toFixed(2);
    };

    // Fonction pour calculer le temps total d'un DRG
    const getTotalTime = (drg) => {
      return (drg.unit_time * drg.sheet_qty).toFixed(2);
    };

    const getRemainingTime = (drg) => {
      const totalTime = drg.unit_time * drg.sheet_qty;
      return (totalTime - drg.real_full_time).toFixed(2);
    };

    const getDrgLink = (drg) => {
      return `/drgs/${drg.id}`;
    };

    // Gérer le changement de statut lors du glisser-déposer
    const onDrop = async (event) => {
    const movedDrg = event.item._underlying_vm_;

    if (!movedDrg) {
        console.error("❌ Impossible de récupérer le DRG déplacé.");
        return;
    }

    // 📌 Récupérer la colonne de destination dynamiquement
    const newStatus = parseInt(event.to.closest(".kanban-column").dataset.status, 10);
    const oldStatus = movedDrg.statu;

    console.log(`🔄 Avant mise à jour | DRG: ${movedDrg.id} Ancien statut: ${oldStatus} Nouveau statut détecté: ${newStatus}`);

    // ❌ Vérifier si le DRG est déplacé dans la même colonne
    if (oldStatus === newStatus) {
        console.warn(`⚠️ Aucun changement détecté, DRG ${movedDrg.id} reste en ${newStatus}`);
        return;
    }

    try {
        // 🔥 Supprimer le DRG de son ancienne colonne
        if (oldStatus !== null && statusDrgs.value[oldStatus]) {
            statusDrgs.value[oldStatus] = statusDrgs.value[oldStatus].filter(drg => drg.id !== movedDrg.id);
        }

        // ✅ Mettre à jour le statut localement
        movedDrg.statu = newStatus;

        // 🔥 Ajouter le DRG dans la nouvelle colonne (éviter les doublons)
        if (!statusDrgs.value[newStatus]) {
            statusDrgs.value[newStatus] = [];
        }
        if (!statusDrgs.value[newStatus].some(drg => drg.id === movedDrg.id)) {
            statusDrgs.value[newStatus].push(movedDrg);
        }

        // 🔄 Forcer Vue à détecter le changement
        statusDrgs.value = { ...statusDrgs.value };

        // 🔥 Envoyer la mise à jour à l'API
        await axios.post("/api/update-drg-status", {
            drg_id: movedDrg.id,
            statu: newStatus,
        });

        console.log(`✅ Mise à jour réussie | DRG: ${movedDrg.id} Nouveau statut: ${newStatus}`);

    } catch (error) {
        console.error("❌ Erreur lors de la mise à jour :", error);
    }
};

    onMounted(fetchDrgs);

    return { statuses, statusDrgs, getTotalTimeForStatus, getTotalTime, getRemainingTime, getDrgLink, onDrop };
  }
};
  </script>
  
  <style scoped>/* ✅ Amélioration générale des cartes */
  .kanban-item {
    background: white;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
    cursor: grab;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    text-align: left;
    gap: 10px; /* Réduction de l’espace entre les colonnes */
    flex-wrap: wrap; /* Permet le wrapping si besoin */
  }
  
  /* ✅ Partie gauche : Image + Nom + Bouton */
  .kanban-item-left {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 150px; /* Réduction de la largeur */
  }
  
  /* ✅ Image du DRG */
  .kanban-item-image {
    width: 150px; /* Taille plus petite */
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 3px;
    border: 1px solid #ccc;
  }
  
  /* ✅ Nom du DRG sous l’image */
  .kanban-item-title {
    font-weight: bold;
    font-size: 11px;
    color: #333;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
  }
  
  /* ✅ Bouton Voir sous l’image */
  .kanban-item-btn {
    margin-top: 3px;
    font-size: 10px;
    width: 100%;
    text-align: center;
    padding: 3px 5px;
    border-radius: 4px;
  }
  
  /* ✅ Partie droite : Infos + Statut */
  .kanban-item-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    font-size: 12px;
  }
  
  /* ✅ Alignement des icônes et informations */
  .kanban-item-meta {
    color: #444;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  
  /* ✅ Icônes pour améliorer la lisibilité */
  .kanban-item-meta::before {
    font-family: "FontAwesome";
    font-size: 12px;
    color: #555;
  }
  
  .kanban-item-meta:nth-child(1)::before { content: "🛠"; } /* Matériau */
  .kanban-item-meta:nth-child(2)::before { content: "📏"; } /* Épaisseur */
  .kanban-item-meta:nth-child(3)::before { content: "📦"; } /* Feuilles */
  .kanban-item-meta:nth-child(4)::before { content: "✅"; } /* Feuilles traitées */
  .kanban-item-meta:nth-child(5)::before { content: "⏳"; } /* Temps total */
  .kanban-item-meta:nth-child(6)::before { content: "⌛"; } /* Temps restant */
  
  /* ✅ Alignement du statut */
  .kanban-item-right small {
    align-self: flex-start;
    margin-top: 5px;
    padding: 4px 6px;
    font-size: 10px;
    border-radius: 4px;
  }
  
  
  .kanban-container {
      display: flex;
      gap: 16px;
      overflow-x: auto;
      padding: 20px;
  }
  
  .kanban-column {
    background: #ffffff; /* Fond blanc propre */
    border-radius: 12px;
    padding: 15px;
    min-width: 280px;
    flex: 1;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #ddd;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
  }
  
  /* Effet au survol */
  .kanban-column:hover {
    box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
  }
  
  /* Titre des colonnes */
  .kanban-column h3 {
    text-align: center;
    padding: 12px;
    font-size: 18px;
    color: white;
    border-radius: 8px;
    margin-bottom: 10px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  /* Spécificité pour la colonne "Sans Machine" */
  .kanban-column[data-machine-id="null"] h3 {
    background: linear-gradient(135deg, #6c757d, #495057) !important; /* Gris dégradé */
  }
  
  /* Liste des tâches dans chaque colonne */
  .kanban-list {
    min-height: 320px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    flex-grow: 1;
    overflow-y: auto;
  }
  
  /* Effet doux sur le scroll */
  .kanban-list::-webkit-scrollbar {
    width: 5px;
  }
  
  .kanban-list::-webkit-scrollbar-thumb {
    background: #bbb;
    border-radius: 5px;
  }
  
  .kanban-list::-webkit-scrollbar-track {
    background: #f8f9fa;
  }
  </style>
  