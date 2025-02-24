<template>
    <div class="kanban-board">
        <!-- Colonne sans machine -->
        <div class="kanban-column card" :class="{ 'active': selectedMachineId === null }">
            <div class="card-header-first">
                <h3 class="card-title">
                    DRGs sans machine ({{ remainingTotalTimeForDrgsWithoutMachine() }} min)
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
            <div class="card-header">
                <h3 class="card-title">
                    {{ machine.name }} ({{ remainingTotalTimeForMachine(machine) }} min)
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
        drop(event, machineId) {
            event.preventDefault();
            if (!this.selectedDrg) return;
            
            // Ajout du token CSRF dans les headers
            fetch(`/drg/${this.selectedDrg.id}/assign-machine`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ machine_id: machineId })
            })
            .then(response => response.json())
            .then(this.updateDrgsAfterAssign)
            .catch(error => console.error("Erreur d'affectation:", error));
        },
        updateDrgsAfterAssign(updatedDrg) {
            // Retirer le DRG de la liste "sans machine"
            const indexWithoutMachine = this.drgsWithoutMachine.findIndex(drg => drg.id === updatedDrg.id);
            if (indexWithoutMachine > -1) {
                this.drgsWithoutMachine.splice(indexWithoutMachine, 1);
            }
            // Mettre à jour la liste des machines
            const machine = this.machines.find(machine => machine.id === updatedDrg.machine_id);
            if (machine && !machine.drgs.find(drg => drg.id === updatedDrg.id)) {
                machine.drgs.push(updatedDrg);
            }
        },
        selectMachine(machineId) {
            this.selectedMachineId = machineId;
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
            return machine.drgs.reduce((total, drg) => total + drg.remaining_time, 0);
        },
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
    flex: 0 0 300px;
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