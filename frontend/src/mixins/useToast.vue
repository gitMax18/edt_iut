<script>
import { useToast } from "vue-toastification";
export default {
    name: "useToast",
    data() {
        return {
            toast: useToast(),
        };
    },
};
</script>

<style lang="scss" scoped></style>
