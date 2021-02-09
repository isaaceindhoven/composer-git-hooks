const { execSync } = require("child_process");

let gitBaseUrl = 'https://github.com/';

try {
  gitBaseUrl = execSync('git config --get remote.origin.url').toString()
      .replace('\n', '')
      .replace('git@github.com:', 'https://github.com/')
      .replace('.git', '');
}
catch (error) {
  console.error('No git repo found, changelog URLs will be incorrect', error);
}

const config = {
  "header": "# Changelog \r\n\r\nAll notable changes to this project will be documented in this file. See [Conventional Commits](https://www.conventionalcommits.org) for the commit message format.",
  "commitUrlFormat": gitBaseUrl + "/commit/{{hash}}",
  "compareUrlFormat": gitBaseUrl + "/compare/{{previousTag}}...{{currentTag}}",
  "types": [
    {"type":"feat","section":"Features","hidden":false},
    {"type":"fix","section":"Bug Fixes","hidden":false},
    {"type":"style","section":"Styling Changes","hidden":false},
    {"type":"perf","section":"Performance","hidden":false},
    {"type":"docs","section":"Documentation","hidden":false},
    {"type":"refactor","section":"Other","hidden":true},
    {"type":"chore","section":"Chore","hidden":true},
    {"type":"test","section":"Testing","hidden":true}
  ]
};
console.log('standard-version config:', config);
module.exports = config;
