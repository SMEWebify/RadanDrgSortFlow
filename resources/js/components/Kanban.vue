<template>
    <div class="kanban-board">
        <!-- Colonne sans machine -->
        <div class="kanban-column card" :class="{ 'active': selectedMachineId === null }">
            <div class="card-header-first">
                <h3 class="card-title">
                    DRGs sans machine ({{ remainingTotalTimeForDrgsWithoutMachine() }} heures)
                </h3>
            </div>
            <div class="card-body">
                <div
                    v-for="drg in drgsWithoutMachine"
                    :key="drg.id"
                    class="kanban-card"
                    @dragstart="dragStart(drg)"
                    draggable="true"
                    >
                    <img 
                        :src="`/images/${drg.drg_name}.png`" 
                        alt="Imbrication" 
                        class="kanban-card-image"
                    />
                    <p>{{ drg.drg_name }}</p>

                    <!-- Afficher le statut avec la classe Bootstrap -->
                    <span :class="getStatuLabel(drg.statu).class">{{ getStatuLabel(drg.statu).text }}</span>
                    
                    <!-- Afficher le temps total et le temps restant -->
                    <p>Temps total : {{ getTotalTime(drg) }} heures</p>
                    <p>Temps restant : {{ getRemainingTime(drg) }} heures</p>
                    
                    <!-- Ajouter un lien vers le DRG -->
                    <a :href="getDrgLink(drg)" class="btn btn-info btn-sm">Voir</a>
                </div>
            </div>
        </div>

        <!-- Colonnes pour chaque machine -->
        <div
            v-for="machine in machines"
            :key="machine.id"
            class="kanban-column card"
            @drop="drop($event, machine.id)"
            @dragover="allowDrop"
            :class="{ 'active': selectedMachineId === machine.id }" 
            @click="selectMachine(machine.id)" 
        >
            <div class="card-header" :style="{ backgroundColor: machine.color || 'grey' }">
                <h3 class="card-title">
                    {{ machine.name }} ({{ remainingTotalTimeForMachine(machine) }} heures)
                </h3>
            </div>
            <div class="card-body">
                <div
                    v-for="drg in machine.drgs"
                    :key="drg.id"
                    class="kanban-card"
                    @dragstart="dragStart(drg)"
                    draggable="true"
                >
                    <img 
                        :src="`/images/${drg.drg_name}.png`" 
                        alt="Imbrication" 
                        class="kanban-card-image"
                    />
                    <p>{{ drg.drg_name }}</p>

                    <!-- Afficher le statut avec la classe Bootstrap -->
                    <span :class="getStatuLabel(drg.statu).class">{{ getStatuLabel(drg.statu).text }}</span>
                    
                    <!-- Afficher le temps total et le temps restant -->
                    <p>Temps total : {{ getTotalTime(drg) }} heures</p>
                    <p>Temps restant : {{ getRemainingTime(drg) }} heures</p>
                    
                    <!-- Ajouter un lien vers le DRG -->
                    <a :href="getDrgLink(drg)" class="btn btn-info btn-sm">Voir</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'Kanban',
    props: {
        drgs: {
            type: Array,
            required: true
        },
        machines: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            selectedMachineId: null,
            selectedDrg: null,
            drgsWithoutMachine: this.drgs.filter(drg => !drg.machine_id)
        };
    },
    methods: {
        dragStart(drg) {
            this.selectedDrg = drg;
        },
        allowDrop(event) {
            event.preventDefault();
        },
        drop(event, targetMachineId) {
            event.preventDefault();
            if (!this.selectedDrg) return;

            const sourceMachineId = this.selectedDrg.machine_id;

            // Si le DRG est déjà assigné à une machine, on l'enlève de la machine source
            if (sourceMachineId !== null && sourceMachineId !== targetMachineId) {
                const sourceMachine = this.machines.find(machine => machine.id === sourceMachineId);
                if (sourceMachine) {
                    const index = sourceMachine.drgs.findIndex(drg => drg.id === this.selectedDrg.id);
                    if (index > -1) {
                        sourceMachine.drgs.splice(index, 1); // Retirer le DRG de la machine source
                    }
                }
            }

            // Mettre à jour la machine cible via un appel API
            fetch(`/drg/${this.selectedDrg.id}/assign-machine`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ machine_id: targetMachineId })
            })
            .then(response => response.json())
            .then(updatedDrg => this.updateDrgsAfterAssign(updatedDrg, targetMachineId))
            .catch(error => console.error("Erreur d'affectation:", error));
        },
        updateDrgsAfterAssign(updatedDrg, targetMachineId) {
            // Mettre à jour la liste des DRGs sans machine si besoin
            const indexWithoutMachine = this.drgsWithoutMachine.findIndex(drg => drg.id === updatedDrg.id);
            if (indexWithoutMachine > -1) {
                this.drgsWithoutMachine.splice(indexWithoutMachine, 1);
            }

            // Trouver la machine cible
            const targetMachine = this.machines.find(machine => machine.id === targetMachineId);
            if (targetMachine) {
                // Recréer un nouveau tableau de DRGs pour la machine cible
                targetMachine.drgs = [...targetMachine.drgs, updatedDrg];
            }

            // Assurer que l'ID de la machine du DRG soit mis à jour
            this.selectedDrg.machine_id = targetMachineId;

            // Remettre selectedDrg à null après l'assignation
            this.selectedDrg = null;
        },
        selectMachine(machineId) {
            this.selectedMachineId = machineId;
        },
        getTotalTime(drg) {
            return Math.round(drg.unit_time * drg.sheet_qty * 100) / 100;
        },
        getRemainingTime(drg) {
            const totalTime = this.getTotalTime(drg);
            return Math.round((totalTime - drg.real_full_time) * 100) / 100;
        },
        getDrgLink(drg) {
            return `/drgs/${drg.id}`; // Le lien vers la page de détail
        },
        getStatuLabel(statu) {
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
        },
        remainingTotalTimeForDrgsWithoutMachine() {
            return this.drgsWithoutMachine.reduce((total, drg) => total + drg.remaining_time, 0);
        },
        remainingTotalTimeForMachine(machine) {
        return machine.drgs.reduce((total, drg) => {
            // Calcul de remaining_time en fonction des données du modèle
            const remainingTime = (drg.unit_time * drg.sheet_qty) - drg.real_full_time;
            // Ajouter au total général
            return total + Math.round(remainingTime * 100) / 100; // arrondi à 2 décimales
        }, 0);
    }
    }
};
</script>
<style scoped>
.kanban-board {
    display: flex;
    justify-content: flex-start;
    gap: 20px;
    padding: 20px;
    overflow-x: auto;
    min-height: calc(100vh - 40px);
}

.kanban-column {
    flex: 0 0 400px;
    background-color: #2A2F3C;
    border: 1px solid #3A3F4C;
    border-radius: 8px;
    min-height: 400px;
    max-height: calc(100vh - 80px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.card-header {
    background-color: #265eee;
    border-bottom: 1px solid #3A3F4C;
    padding: 1rem;
}

.card-header-first {
    background-color: #bd2d2d;
    border-bottom: 1px solid #3A3F4C;
    padding: 1rem;
}


.card-title {
    color: #FFFFFF;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.card-body {
    padding: 1rem;
    overflow-y: auto;
    flex: 1;
}

.kanban-card {
    background-color: #ffffff;
    border: 1px solid #4A4F5C;
    margin: 8px 0;
    padding: 12px;
    border-radius: 6px;
    cursor: move;
    transition: all 0.2s ease;
    color: #000000;
}

.kanban-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    background-color: #4A4F5C;
}

.kanban-card p {
    margin: 0;
}

.kanban-column.active {
    border: 2px solid #3B82F6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

/* Scrollbar styling */
.card-body::-webkit-scrollbar {
    width: 6px;
}

.card-body::-webkit-scrollbar-track {
    background: #2A2F3C;
}

.card-body::-webkit-scrollbar-thumb {
    background: #4A4F5C;
    border-radius: 3px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: #5A5F6C;
}
</style>