<template>
   <div class="row okvir">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-dark">
                <h4 class="float-start text-light">To do app</h4>
               <button class="btn btn-success float-end" @click="createTask()">
                                    Create task
                                </button>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>
                                Title
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Completed
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="task in tasks" :key="task.id">
                            <td>{{task.title}}</td>
                             <td>{{task.description}}</td>
                              <td>{{task.completed
                                 ? "YES"
                                 : "NO"}}</td>
                              <td>
                                <button class="btn btn-success" @click="updateTask(task)">
                                    Update
                                </button>
                                 <button class="btn btn-danger" @click="deleteTask(task)">
                                    Delete
                                </button>
                                <button class="btn btn-primary" @click="complete(task.id)">
                                    Complete
                                </button>
                                <button class="btn btn-secondary" @click="incomplete(task.id)">
                                    Incomplete
                                </button>
                              </td>
                        </tr>
                    </tbody>
                </table>
               </div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="exampleModal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{!updatePart ? 'Add task' : 'Update task'}}
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="title"
                                        v-model="taskData.title"
                                    />
                                    <p class="text-danger"
                                    v-if="taskErrors.title">
                                    Title is required
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="description"
                                        v-model="taskData.description"
                                    />
                                    <p class="text-danger"
                                    v-if="taskErrors.description">
                                    Description is required
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="completed">Completed</label>
                                    <select
                                        name="completed"
                                        class="form-control"
                                        v-model="taskData.completed"
                                    >
                                        <option value="1">YES</option>
                                        <option value="0">NO</option>
                                    </select>
                                    <p class="text-danger"
                                    v-if="taskErrors.completed">
                                    Select option
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
                        <button type="button" @click="!updatePart ? storeTask() : updateNewTask()" class="btn btn-success">
                            {{!updatePart ? 'Store' : 'Update'}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
            </div>
        </div>
    </div>
   </div>

</template>

<script>

export default {
    data() {
        return {
            updatePart: false,
            tasks: [],
           taskData: {
            id: "",
             title: "",
            description: "",
            completed: "",
            user_id: 1
           },
           taskErrors: {
             title: false,
            description:false,
            completed:false,
           }
        };
    },
    methods: {

    getTasks() {
        axios.get('api/tasks').then((response)=> {
            //console.log(response.data.tasks);
            this.tasks = response.data.tasks;

        })

    },
    createTask() {
        this.updatePart = false;
        this.taskData.title = this.taskData.description = this.taskData.completed = ''
        $('#exampleModal').modal('show');
    },
        storeTask() {
            this.taskData.title == '' ? this.taskErrors.title = true : this.taskErrors.title = false
            this.taskData.description == '' ? this.taskErrors.description = true : this.taskErrors.description = false
            this.taskData.completed == '' ? this.taskErrors.completed = true : this.taskErrors.completed = false

            if(this.taskData.title && this.taskData.description && this.taskData.completed) {
                 axios.post('api/tasks', this.taskData)
           .then((response)=> {
            //console.log(response.data);
            //alert("Task added");
            this.getTasks();
            $('#exampleModal').modal('hide');
            //modal-backdrop
           })

            }

          
        },
        updateTask(task) {

            this.updatePart = true;
            //console.log(task);
            this.taskData.id = task.id;
            this.taskData.title = task.title;
            this.taskData.description = task.description;
            this.taskData.completed = task.completed;
            $('#exampleModal').modal('show');
        },
        updateNewTask() {
            this.taskData.title == '' ? this.taskErrors.title = true : this.taskErrors.title = false
            this.taskData.description == '' ? this.taskErrors.description = true : this.taskErrors.description = false

            if(this.taskData.title && this.taskData.description) {
             axios.put('api/tasks/' + this.taskData.id, this.taskData)
           .then((response)=> {
            //console.log(response.data);
            //alert("Task updated");
            this.getTasks();
            $('#exampleModal').modal('hide');
           })
            }
        },
        deleteTask(task) {
            if(confirm('Are you sure?')){
            axios.delete('api/tasks/' + task.id, task)
           .then((response)=> {
            //console.log(response.data);
            //alert("Task deleted");
            this.getTasks();
            $('#exampleModal').modal('hide');
           })
            }
        },
        complete(id){
            axios.patch('api/tasks/' + id + '/complete')
           .then((response)=> {
            //console.log(response.data);
            this.getTasks();
           })
            
        },
        incomplete(id) {
            axios.patch('api/tasks/' + id + '/incomplete')
           .then((response)=> {
            //console.log(response.data);
            this.getTasks();
           })
        }
    },
    mounted() {
        this.getTasks();
    }
};
</script>

<style scoped>
h1 {
    color: black;
}
.okvir {
margin: 10px;
}
button {
    margin: 5px;
}
</style>
