<main>
  <div class="box">
    <h2 class="title">Tiptap</h2>

    <div x-data="editor('')">
      <template x-if="isLoaded()">
        <div class="menu">
          <button @click="toggleHeading({ level: 1 })" :class="{ 'is-active': isActive('heading', { level: 1 }, updatedAt) }">
            H1
          </button>
          <button @click="toggleBold()" :class="{ 'is-active' : isActive('bold', updatedAt) }">
            Bold
          </button>
          <button @click="toggleItalic()" :class="{ 'is-active' : isActive('italic', updatedAt) }">
            Italic
          </button>
          <button @click="toggleStrike()" :class="{ 'is-active' : isActive('strike', updatedAt) }">
            Strike
          </button>
        </div>
      </template>

      <div class="bubble-menu">
        <button @click="toggleBold()">
          Bold
        </button>
        <button @click="toggleItalic()">
          Italic
        </button>
        <button @click="toggleCode()">
          Код
        </button>

        <button @click="toggleStrike()">
          Strike
        </button>
      </div>

      <div x-ref="element"></div>

      <textarea type="hidden" name="content" id="hidden-content" hidden><?= $data['md']; ?> </textarea>
    </div>
</main>

<script type="module" src="/assets/js/editor-test/editor.js"></script>

<style>
  .tiptap {
    padding: 0.5rem 1rem;
    margin: 1rem 0;
    border: 1px solid #eee;
  }

  .tiptap img {
    max-width: 100%;
  }
</style>