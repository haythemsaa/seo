import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useProjectsStore = defineStore('projects', () => {
    const projects = ref([]);
    const currentProject = ref(null);
    const loading = ref(false);
    const error = ref(null);

    const activeProjects = computed(() =>
        projects.value.filter(p => p.is_active)
    );

    const inactiveProjects = computed(() =>
        projects.value.filter(p => !p.is_active)
    );

    const totalKeywords = computed(() =>
        projects.value.reduce((sum, p) => sum + (p.keywords_count || 0), 0)
    );

    const totalBacklinks = computed(() =>
        projects.value.reduce((sum, p) => sum + (p.backlinks_count || 0), 0)
    );

    const setProjects = (data) => {
        projects.value = data;
    };

    const addProject = (project) => {
        projects.value.push(project);
    };

    const updateProject = (id, updates) => {
        const index = projects.value.findIndex(p => p.id === id);
        if (index !== -1) {
            projects.value[index] = { ...projects.value[index], ...updates };
        }
    };

    const removeProject = (id) => {
        const index = projects.value.findIndex(p => p.id === id);
        if (index !== -1) {
            projects.value.splice(index, 1);
        }
    };

    const setCurrentProject = (project) => {
        currentProject.value = project;
    };

    const getProjectById = (id) => {
        return projects.value.find(p => p.id === id);
    };

    return {
        projects,
        currentProject,
        loading,
        error,
        activeProjects,
        inactiveProjects,
        totalKeywords,
        totalBacklinks,
        setProjects,
        addProject,
        updateProject,
        removeProject,
        setCurrentProject,
        getProjectById,
    };
});
