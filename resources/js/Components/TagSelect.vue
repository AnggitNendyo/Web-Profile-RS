<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    options: {
        type: Array,
        default: () => []
    },
    labelKey: {
        type: String,
        default: 'name'
    },
    valueKey: {
        type: String,
        default: 'id'
    },
    placeholder: {
        type: String,
        default: 'Pilih opsi...'
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});

const selectOption = (option) => {
    const value = option[props.valueKey];
    if (!props.modelValue.includes(value)) {
        emit('update:modelValue', [...props.modelValue, value]);
    }
    // Optionally close dropdown if we only want one, but this is multi-select
};

const removeOption = (value) => {
    emit('update:modelValue', props.modelValue.filter(v => v !== value));
};

const unselectedOptions = computed(() => {
    return props.options.filter(opt => !props.modelValue.includes(opt[props.valueKey]));
});

const getLabel = (value) => {
    const option = props.options.find(opt => opt[props.valueKey] === value);
    return option ? option[props.labelKey] : value;
};
</script>

<template>
    <div class="relative w-full" ref="dropdownRef">
        <!-- Input Display Area -->
        <div 
            @click="toggleDropdown"
            class="mt-1 flex flex-wrap items-center gap-2 w-full rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 cursor-pointer min-h-[42px] transition-colors"
        >
            <span v-if="modelValue.length === 0" class="text-gray-400 text-sm select-none">{{ placeholder }}</span>
            
            <!-- Tags -->
            <span 
                v-for="val in modelValue" 
                :key="val"
                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-blue-100 text-blue-800 text-xs font-medium border border-blue-200"
            >
                {{ getLabel(val) }}
                <button 
                    type="button" 
                    @click.stop="removeOption(val)" 
                    class="text-blue-600 hover:text-blue-900 focus:outline-none hover:bg-blue-200 rounded-full p-0.5 transition-colors"
                >
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </span>
        </div>

        <!-- Dropdown Menu -->
        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div 
                v-if="isOpen" 
                class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
            >
                <div v-if="unselectedOptions.length === 0" class="relative cursor-default select-none py-2 px-4 text-gray-500 text-sm italic text-center">
                    Semua opsi telah dipilih.
                </div>
                <ul v-else>
                    <li 
                        v-for="option in unselectedOptions" 
                        :key="option[valueKey]"
                        @click="selectOption(option)"
                        class="relative cursor-pointer select-none py-2 pl-3 pr-9 text-gray-900 hover:bg-blue-50 hover:text-blue-900 border-b border-gray-50 last:border-0 transition-colors"
                    >
                        <span class="block truncate font-medium">
                            {{ option[labelKey] }}
                        </span>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>
