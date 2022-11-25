import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        sessionYear: '',
        semesterName: '',
        departments: [],
        registeredCoursesByLecturer:[],
    },
    actions: {
        getCurrentSession({commit}) {
            axios.get('get-activated-session')
                .then(response => {
                    // console.log(response.data);
                    commit('SET_CURRENT_SESSION', response.data);
                })
                .catch(err=>{
                    $this.toasted.show("Unable to load current session");
                })
        },
        getDepartments({commit}) {
            axios.get('department/all')
                .then(response => {
                    // console.log(`Department: ${response.data}`);
                    commit('GET_ALL_DEPARTMENT', response.data);
                })
                .catch(err => this.$toasted.show("Unable to load departments"));
        },
        getRegisteredCourse({commit}) {
            axios.get('course-lecturer/registered-course')
                .then(response => {
                    // console.log(`Registered course: ${response.data}`);
                    commit('GET_REGISTERED_COURSE_BY_LECTURER', response.data);
                })
                .catch(err => this.$toasted.show("Unable to load registered courses"));
        }

    },
    mutations: {
        SET_CURRENT_SESSION (state, currentSession) {
            state.sessionYear = `${currentSession.sessionYearInitial}/${currentSession.sessionYearFinal}`;
            state.semesterName = currentSession.semesterName;
        },
        GET_ALL_DEPARTMENT (state, allDepartment) {
            state.departments = allDepartment;
        },
        GET_REGISTERED_COURSE_BY_LECTURER (state, registeredCourse) {
            state.registeredCoursesByLecturer = registeredCourse;
        }
    }
});
