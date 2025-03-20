<template>
  <div class="kanban-container">

    <!-- Colonne des DRGs sans machine -->
    <div class="kanban-column" data-machine-id="null">
      <h3 style="background-color: #ccc">Sans Machine</h3> <span class="kanban-column-total">⏳ Total: {{ getTotalTimeForUnassigned() }} h</span>
      <draggable 
          v-model="unassignedDrgs"
          group="drgs"
          itemKey="id"
        class="kanban-list vuedraggable-fade-move"
          @end="onDrop($event)" 
        >
        <template #item="{ element }">
          <div class="kanban-item">
            <!-- Colonne gauche (Image + Bouton "Voir") -->
            <div class="kanban-item-left">
              <img 
                :src="`/images/${element.drg_name}.png`" 
                alt="DRG Image" 
                class="kanban-item-image"
              />
              <p class="kanban-item-title">{{ element.drg_name }}</p>
              
              <!-- Bouton Voir sous l'image -->
              <a :href="getDrgLink(element)" class="btn btn-info btn-sm kanban-item-btn">🔍 Voir</a>
            </div>

            <!-- Colonne droite (Infos + Statut) -->
            <div class="kanban-item-right">
              <!-- Matériau et épaisseur -->
              <p class="kanban-item-meta">🛠 Matériau : {{ element.material }}</p>
              <p class="kanban-item-meta">📏 Épaisseur : {{ element.thickness }} mm</p>

              <!-- Quantité -->
              <p class="kanban-item-meta">📦 Feuilles : {{ element.sheet_qty }}</p>
              <p class="kanban-item-meta">✅ Feuilles traitées : {{ element.sheet_qty_done }}</p>

              <!-- Temps total et restant -->
              <p class="kanban-item-meta">⏳ Temps total : {{ getTotalTime(element) }} h</p>
              <p class="kanban-item-meta">⌛ Temps restant : {{ getRemainingTime(element) }} h</p>

              <!-- Statut sous les infos -->
              <small v-if="getStatuLabel(element.statu)" 
                    :class="getStatuLabel(element.statu).class">
                {{ getStatuLabel(element.statu).text }}
              </small>
            </div>
          </div>
        </template>

      </draggable>
    </div>

    <!-- Colonnes des machines -->
    <div v-for="machine in machines" :key="machine.id" class="kanban-column" :data-machine-id="machine.id">

      <h3 :style="{ backgroundColor: machine.color || '#808080' }">
        {{ machine.name }}
      </h3>
      <span class="kanban-column-total">⏳ Total: {{ getTotalTimeForMachine(machine) }} h</span>
      <draggable 
        v-model="machine.drgs" 
        group="drgs" 
        itemKey="id" 
        class="kanban-list vuedraggable-fade-move"
        @end="onDrop($event)"
      >
      <template #item="{ element }">
        <div class="kanban-item">
          <!-- Colonne gauche (Image + Bouton "Voir") -->
          <div class="kanban-item-left">
            <img 
              :src="`/images/${element.drg_name}.png`" 
              alt="DRG Image" 
              class="kanban-item-image"
            />
            <p class="kanban-item-title">{{ element.drg_name }}</p>
            
            <!-- Bouton Voir sous l'image -->
            <a :href="getDrgLink(element)" class="btn btn-info btn-sm kanban-item-btn">🔍 Voir</a>
          </div>

          <!-- Colonne droite (Infos + Statut) -->
          <div class="kanban-item-right">
            <!-- Matériau et épaisseur -->
            <p class="kanban-item-meta">🛠 Matériau : {{ element.material }}</p>
            <p class="kanban-item-meta">📏 Épaisseur : {{ element.thickness }} mm</p>

            <!-- Quantité -->
            <p class="kanban-item-meta">📦 Feuilles : {{ element.sheet_qty }}</p>
            <p class="kanban-item-meta">✅ Feuilles traitées : {{ element.sheet_qty_done }}</p>

            <!-- Temps total et restant -->
            <p class="kanban-item-meta">⏳ Temps total : {{ getTotalTime(element) }} h</p>
            <p class="kanban-item-meta">⌛ Temps restant : {{ getRemainingTime(element) }} h</p>

            <!-- Statut sous les infos -->
            <small v-if="getStatuLabel(element.statu)" 
                  :class="getStatuLabel(element.statu).class">
              {{ getStatuLabel(element.statu).text }}
            </small>
          </div>
        </div>
      </template>

      </draggable>
    </div>

  </div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import draggable from "vuedraggable";
import axios from "axios";

export default {
  components: { draggable },
  setup() {
    const machines = ref([]);
    const unassignedDrgs = ref([]);

    const getTotalTime = (drg) => {
    return (drg.unit_time * drg.sheet_qty).toFixed(2); // Temps total en heures
    };

    const getRemainingTime = (drg) => {
      const totalTime = drg.unit_time * drg.sheet_qty;
      return (totalTime - drg.real_full_time).toFixed(2); // Temps restant
    };

    const getDrgLink = (drg) => {
      return `/drgs/${drg.id}`; // URL de détail du DRG
    };

    const getTotalTimeForMachine = (machine) => {
      if (!machine || !machine.drgs) return "0.00"; // Vérification de sécurité
      return machine.drgs.reduce((total, drg) => total + parseFloat(getTotalTime(drg) || 0), 0).toFixed(2);
    };

    const getTotalTimeForUnassigned = () => {
      if (!unassignedDrgs.value) return "0.00"; // Vérification de sécurité
      return unassignedDrgs.value.reduce((total, drg) => total + parseFloat(getTotalTime(drg) || 0), 0).toFixed(2);
    };

    const getStatuLabel = (statu) => {
            switch (statu) {
                case 1:
                    return { text: 'A planifier', class: 'badge badge-warning' };
                case 2:
                    return { text: 'Planifier', class: 'badge badge-primary' };
                case 3:
                    return { text: 'En cours', class: 'badge badge-info' };
                case 4:
                    return { text: 'A refaire', class: 'badge badge-danger' };
                case 5:
                    return { text: 'Terminer', class: 'badge badge-success' };
                case 6:
                    return { text: 'Supprimer', class: 'badge badge-secondary' };
                case 7:
                    return { text: 'Stopper', class: 'badge badge-dark' };
                default:
                    return { text: 'Inconnu', class: 'badge badge-light' };
            }
        };

    const fetchMachines = async () => {
      try {
        const response = await axios.get("/api/kanban-machines");

        machines.value = response.data.machines;
        unassignedDrgs.value = response.data.unassignedDrgs; // Récupère les DRGs sans machine

        console.log("Machines chargées :", machines.value);
        console.log("DRGs sans machine :", unassignedDrgs.value);
      } catch (error) {
        console.error("Erreur lors du chargement des machines :", error);
      }
    };

    // Mettre à jour l'ID de la machine d'un DRG déplacé
    const onDrop = async (event) => {
      const movedDrg = event.item._underlying_vm_;

      if (!movedDrg) {
        console.error("Impossible de récupérer le DRG déplacé.");
        return;
      }

      // 📌 Récupérer dynamiquement la machine de destination
      const newMachineElement = event.to.closest(".kanban-column");
      const newMachineId = newMachineElement ? parseInt(newMachineElement.dataset.machineId, 10) : null;

      const oldMachineId = movedDrg.machine_id; // ID avant déplacement

      console.log("Avant mise à jour | DRG:", movedDrg.id, "Ancienne machine:", oldMachineId, "Nouvelle machine détectée:", newMachineId);

      try {
        // 🔥 Suppression propre du DRG de son ancienne colonne
        if (oldMachineId === null) {
          unassignedDrgs.value = unassignedDrgs.value.filter(drg => drg.id !== movedDrg.id);
        } else {
          const oldMachine = machines.value.find(m => m.id === oldMachineId);
          if (oldMachine) {
            oldMachine.drgs = oldMachine.drgs.filter(drg => drg.id !== movedDrg.id);
          }
        }

        // 🔥 Mise à jour de l'ID avant d'ajouter le DRG
        movedDrg.machine_id = newMachineId;

        // 🔥 Ajout propre dans la nouvelle colonne (sans doublons)
        if (newMachineId === null) {
          if (!unassignedDrgs.value.some(drg => drg.id === movedDrg.id)) {
            unassignedDrgs.value.push(movedDrg);
          }
        } else {
          const newMachine = machines.value.find(m => m.id === newMachineId);
          if (newMachine) {
            if (!newMachine.drgs.some(drg => drg.id === movedDrg.id)) {
              newMachine.drgs.push(movedDrg);
            }
          }
        }

        // 🔄 Mise à jour forcée des machines pour éviter le cache Vue
        machines.value = [...machines.value];

        // Envoi de la mise à jour immédiatement à l'API
        await axios.post("/api/update-drg-machine", {
          drg_id: movedDrg.id,
          machine_id: newMachineId,
        });

        console.log("Mise à jour réussie | DRG:", movedDrg.id, "Nouvelle machine:", newMachineId);

        } catch (error) {
          console.error("Erreur lors de la mise à jour :", error);
        }
    };

    onMounted(fetchMachines);

    return { machines, unassignedDrgs, onDrop ,getTotalTime, getRemainingTime, getDrgLink, getStatuLabel, getTotalTimeForMachine, getTotalTimeForUnassigned};
  },
};
</script>

<style scoped>
.kanban-item {
  background: white;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 8px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease-in-out;
  cursor: grab;
  border: 1px solid #dee2e6;
  display: flex;
  align-items: center;
  text-align: left;
  gap: 15px; /* Espace entre les colonnes */
}

/* Partie gauche : Image + Nom + Bouton */
.kanban-item-left {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 200px;
}

.kanban-item-image {
  width: 200px;
  object-fit: contain;
  margin-bottom: 5px;
}

.kanban-item-title {
  font-weight: bold;
  font-size: 12px;
  color: #333;
  text-align: center;
}

/* Bouton Voir */
.kanban-item-btn {
  margin-top: 5px;
  width: 80%;
  text-align: center;
}

/* Partie droite : Infos + Statut */
.kanban-item-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.kanban-item-meta {
  color: #555;
}

/* Statut aligné en bas */
.kanban-item-right small {
  align-self: flex-start;
  margin-top: 10px;
  padding: 5px 10px;
  font-size: 12px;
  border-radius: 5px;
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
