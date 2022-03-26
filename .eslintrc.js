module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: ['airbnb', 'prettier'],
  parserOptions: {
    ecmaFeatures: {
      jsx: true,
    },
    ecmaVersion: 12,
    sourceType: 'module',
  },
  rules: {
    'no-param-reassign': 'off',
    'react/react-in-jsx-scope': 'off',
    'react/no-array-index-key': 'off',
    'react/no-unknown-property': 'off',
    'react/no-unescaped-entities': 'off',
    'jsx-a11y/anchor-is-valid': 'off',
    'jsx-a11y/alt-text': 'off',
    'import/no-extraneous-dependencies': 'off',
    'import/prefer-default-export': 'off',
    'arrow-body-style': 'off',
    'import/extensions': ['error', 'always'],
    'jsx-a11y/click-events-have-key-events': 'off',
    'jsx-a11y/no-noninteractive-element-interactions': 'off',
    'react/jsx-no-target-blank': 'off',
  },
};
