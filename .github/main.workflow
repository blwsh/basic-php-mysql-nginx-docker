workflow "New workflow" {
  on = "push"
  resolves = ["GitHub Action for Google Cloud"]
}

action "GitHub Action for Google Cloud" {
  uses = "actions/gcloud/cli@ba93088eb19c4a04638102a838312bb32de0b052"
}
