<template>
    <div class=" bg-white border rounded-lg shadow-sm">
        <CardHeader v-if="title || description" class="flex justify-between items-center">
            <CardTitle v-if="title" class="text-sm font-medium ">
                {{ title }}
            </CardTitle>
            <component :is="icon" class="h-8 w-8 text-muted-foreground" />
        </CardHeader>
        <CardContent v-if="numberValue || change">
            <div class="text-2xl font-bold">{{ numberValue }}</div>
            <p class="text-xs text-muted-foreground">{{ change }}</p>
        </CardContent>
    </div>
</template>


<script>
import CardHeader from "@/Shared/CardHeader.vue";
import CardTitle from "@/Shared/CardTitle.vue";
import CardDescription from "@/Shared/CardDescription.vue";
import CardContent from "@/Shared/CardContent.vue";
import CardFooter from "@/Shared/CardFooter.vue";
import { cn } from "@/Utils/utils";

export default {
  name: "Card",
  components: {
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
    CardFooter,
  },
  props: {
    className: {
      type: String,
      default: "",
    },
    title: {
      type: String,
      default: null, // Optional
    },
    description: {
      type: String,
      default: null, // Optional
    },
    content: {
      type: String,
      default: null, // Optional
    },
    action: {
      type: String,
      default: null, // Optional
    },
    icon: {
        type: Function,
    },
    numberValue: {
    type: String,
    default: null, // Optional
    },
    change: {
        type: String,
        default: null, // Optional
    },
  },
  computed: {
    computedClass() {
      return cn("rounded-lg border bg-card text-card-foreground shadow-sm", this.className);
    },
  },
};
</script>