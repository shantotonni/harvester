<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Push Notification List']">
            </breadcrumb>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input v-model="query" type="text" class="form-control" placeholder="Search by name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="createNotification">
                                            <i class="fas fa-plus"></i>
                                            Send Notification
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i>
                                            Reload
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Title</th>
                                            <th class="text-left">Message</th>
                                            <th class="text-left">Image</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(notification, i) in notifications" :key="notification.id" v-if="notifications.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ notification.title }}</td>
                                            <td class="text-left">{{ notification.message }}</td>
                                            <td class="text-left">{{ notification.image }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllNotification() : searchData()"
                                    ></pagination>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <skeleton-loader :row="14"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  Modal content for the above example -->
        <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Notification</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="store()" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="title" v-model="form.title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                                            <div class="error" v-if="form.errors.has('title')" v-html="form.errors.get('title')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input @change="changeImage($event)" type="file" name="image" class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                                            <div class="error" v-if="form.errors.has('image')" v-html="form.errors.get('image')" />
                                            <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px" width="40px">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Message</label>
                                            <input type="text" name="message" style="height: 200px" v-model="form.message" class="form-control" :class="{ 'is-invalid': form.errors.has('message') }">
<!--                                            <vue-editor name="details" v-model="form.message" :class="{ 'is-invalid': form.errors.has('message') }"></vue-editor>-->
                                            <div class="error" v-if="form.errors.has('message')" v-html="form.errors.get('message')"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                                <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Send" }} Notification</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

export default {
    name: "List",
    data() {
        return {
            notifications: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                title: '',
                message: '',
                image: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllNotification();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Notification List | Harvester';
        this.getAllNotification();
    },
    methods: {
        getAllNotification() {
            this.isLoading = true;
            axios.get('/api/notification-list?page=' + this.pagination.current_page).then((response) => {
                this.notifications = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/notification-list/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.notifications = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllNotification();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#notificationModal").modal("hide");
        },
        createNotification() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#notificationModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/notification-list").then(response => {
                //$("#notificationModal").modal("hide");
                //this.getAllNotification();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        changeImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.image = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage() {
            let img = this.form.image;
            if (img.length > 100) {
                return this.form.image;
            } else {
                return window.location.origin + "/harvester/public/images/notification/" + this.form.image;
            }
        },

    },
}
</script>

<style scoped>

</style>
