# Contributing to SEO Master Pro

First off, thank you for considering contributing to SEO Master Pro! It's people like you that make SEO Master Pro such a great tool.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Workflow](#development-workflow)
- [Style Guides](#style-guides)
- [Commit Messages](#commit-messages)
- [Pull Requests](#pull-requests)
- [Testing](#testing)

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code. Please report unacceptable behavior to [contact@seo-master-pro.fr](mailto:contact@seo-master-pro.fr).

### Our Pledge

We pledge to make participation in our project a harassment-free experience for everyone, regardless of age, body size, disability, ethnicity, gender identity and expression, level of experience, nationality, personal appearance, race, religion, or sexual identity and orientation.

### Our Standards

**Examples of behavior that contributes to creating a positive environment include:**

- Using welcoming and inclusive language
- Being respectful of differing viewpoints and experiences
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

**Examples of unacceptable behavior include:**

- Trolling, insulting/derogatory comments, and personal or political attacks
- Public or private harassment
- Publishing others' private information without explicit permission
- Other conduct which could reasonably be considered inappropriate in a professional setting

## Getting Started

### Prerequisites

Before you begin, ensure you have the following installed:

- PHP 8.3 or higher
- Composer 2.x
- Node.js 20.x or higher
- MySQL 8.0+ or PostgreSQL 16+
- Redis 7.x

### Fork and Clone

1. Fork the repository on GitHub
2. Clone your fork locally:

```bash
git clone https://github.com/YOUR_USERNAME/seo.git
cd seo
```

3. Add the upstream repository:

```bash
git remote add upstream https://github.com/haythemsaa/seo.git
```

### Local Development Setup

1. **Install dependencies:**

```bash
composer install
npm install
```

2. **Configure environment:**

```bash
cp .env.example .env
php artisan key:generate
```

3. **Set up database:**

```bash
php artisan migrate
php artisan db:seed
```

4. **Build assets:**

```bash
npm run dev
```

5. **Start development server:**

```bash
php artisan serve
```

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates. When you create a bug report, include as many details as possible:

**Bug Report Template:**

```markdown
**Description:**
A clear and concise description of the bug.

**To Reproduce:**
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

**Expected Behavior:**
What you expected to happen.

**Screenshots:**
If applicable, add screenshots.

**Environment:**
- OS: [e.g., Ubuntu 22.04]
- PHP Version: [e.g., 8.3.1]
- Browser: [e.g., Chrome 120]
- Version: [e.g., 1.0.0]

**Additional Context:**
Any other context about the problem.
```

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Use a clear and descriptive title**
- **Provide a detailed description** of the suggested enhancement
- **Explain why this enhancement would be useful**
- **List some examples** of how it would be used

### Your First Code Contribution

Unsure where to begin? Look for issues labeled:

- `good first issue` - Simple issues perfect for newcomers
- `help wanted` - Issues where we need community help
- `bug` - Confirmed bugs
- `enhancement` - New features or improvements

### Pull Requests

1. **Create a branch** from `develop`:

```bash
git checkout develop
git pull upstream develop
git checkout -b feature/your-feature-name
```

2. **Make your changes** following our style guides

3. **Write or update tests** for your changes

4. **Run tests** to ensure everything passes:

```bash
php artisan test
npm run test
```

5. **Commit your changes** with clear commit messages

6. **Push to your fork:**

```bash
git push origin feature/your-feature-name
```

7. **Open a Pull Request** to the `develop` branch

## Development Workflow

### Branch Naming

- `feature/` - New features (e.g., `feature/add-keyword-grouping`)
- `bugfix/` - Bug fixes (e.g., `bugfix/fix-login-redirect`)
- `hotfix/` - Critical production fixes
- `refactor/` - Code refactoring
- `docs/` - Documentation updates
- `test/` - Test additions or updates

### Branching Strategy

We use Git Flow:

- `main` - Production-ready code
- `develop` - Integration branch for features
- `feature/*` - New features
- `hotfix/*` - Critical fixes for production

## Style Guides

### PHP Style Guide

We follow **PSR-12** coding standards.

**Key points:**

```php
<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    /**
     * Create a new project.
     *
     * @param array $data
     * @return Project
     */
    public function create(array $data): Project
    {
        // Validate data
        $validated = $this->validate($data);

        // Create project
        $project = Project::create($validated);

        // Log creation
        Log::info('Project created', ['project_id' => $project->id]);

        return $project;
    }

    /**
     * Validate project data.
     */
    private function validate(array $data): array
    {
        // Validation logic
        return $data;
    }
}
```

**Run PHP CodeSniffer:**

```bash
vendor/bin/phpcs --standard=PSR12 app/
```

**Auto-fix with PHP-CS-Fixer:**

```bash
vendor/bin/php-cs-fixer fix
```

### JavaScript/Vue.js Style Guide

We follow **Vue.js Style Guide** (Priority A + B).

**Key points:**

```vue
<script setup>
import { ref, computed, onMounted } from 'vue';
import { useProjectsStore } from '@/stores/projects';

// Props
const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

// Emits
const emit = defineEmits(['update', 'delete']);

// Composables
const projectsStore = useProjectsStore();

// State
const isEditing = ref(false);
const formData = ref({ ...props.project });

// Computed
const isValid = computed(() => {
    return formData.value.name && formData.value.url;
});

// Methods
const handleSubmit = async () => {
    if (!isValid.value) return;

    try {
        await projectsStore.updateProject(formData.value);
        emit('update', formData.value);
        isEditing.value = false;
    } catch (error) {
        console.error('Failed to update project:', error);
    }
};

// Lifecycle
onMounted(() => {
    // Component mounted
});
</script>

<template>
    <div class="project-card">
        <h3>{{ project.name }}</h3>
        <p>{{ project.url }}</p>

        <button
            v-if="!isEditing"
            @click="isEditing = true"
            class="btn btn-primary"
        >
            Edit
        </button>
    </div>
</template>

<style scoped>
.project-card {
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
}
</style>
```

**Run ESLint:**

```bash
npm run lint
```

### CSS/SCSS Style Guide

- Use Bootstrap 5 utility classes when possible
- Follow BEM naming for custom components
- Use CSS variables for theming
- Keep specificity low

```scss
// Variables
$primary-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);

// Custom component
.custom-card {
    @apply rounded-lg shadow-md p-4;

    &__header {
        @apply flex items-center justify-between mb-3;
    }

    &__title {
        @apply text-lg font-semibold;
    }

    &--highlighted {
        border: 2px solid var(--bs-primary);
    }
}
```

## Commit Messages

We follow **Conventional Commits** specification.

### Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- `feat` - New feature
- `fix` - Bug fix
- `docs` - Documentation changes
- `style` - Code style changes (formatting, missing semicolons, etc.)
- `refactor` - Code refactoring
- `test` - Adding or updating tests
- `chore` - Maintenance tasks

### Examples

```
feat(projects): add project archiving functionality

Add ability to archive projects instead of deleting them.
Archived projects are hidden from the main list but can be restored.

Closes #123
```

```
fix(keywords): resolve duplicate keyword import issue

Previously, bulk import would create duplicates if keywords already existed.
Now checks for existing keywords before importing.

Fixes #456
```

```
docs(api): update authentication documentation

Add examples for token refresh and revocation.
Clarify rate limiting behavior.
```

## Pull Requests

### Pull Request Template

When opening a PR, use this template:

```markdown
## Description
Brief description of changes.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Related Issues
Closes #123

## How Has This Been Tested?
- [ ] Unit tests
- [ ] Integration tests
- [ ] Manual testing

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
- [ ] Tests added/updated
- [ ] All tests passing
- [ ] No new warnings
```

### Review Process

1. At least one maintainer must approve
2. All CI checks must pass
3. Code coverage must not decrease
4. No merge conflicts
5. Follows coding standards

## Testing

### Running Tests

```bash
# All tests
php artisan test

# Specific test suite
php artisan test --testsuite=Feature

# With coverage
php artisan test --coverage --min=80

# Frontend tests
npm run test
```

### Writing Tests

**Feature Test Example:**

```php
public function test_user_can_create_project(): void
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/projects', [
        'name' => 'Test Project',
        'url' => 'https://example.com',
        'country' => 'FR',
        'language' => 'fr',
    ]);

    $response->assertRedirect('/projects');
    $this->assertDatabaseHas('projects', [
        'name' => 'Test Project',
    ]);
}
```

**Unit Test Example:**

```php
public function test_validates_url_format(): void
{
    $validator = Validator::make(
        ['url' => 'invalid-url'],
        ['url' => 'required|url']
    );

    $this->assertTrue($validator->fails());
}
```

## Code Review

When reviewing code, check for:

- **Functionality**: Does it work as intended?
- **Tests**: Are there adequate tests?
- **Performance**: Are there any performance concerns?
- **Security**: Are there any security vulnerabilities?
- **Style**: Does it follow our style guides?
- **Documentation**: Is it well-documented?

## Recognition

Contributors will be recognized in:

- `CONTRIBUTORS.md` file
- Release notes for significant contributions
- Annual contributor acknowledgments

## Questions?

If you have questions, feel free to:

- Open a discussion on GitHub
- Join our Discord community
- Email us at dev@seo-master-pro.fr

Thank you for contributing to SEO Master Pro! 🎉
