import axios from 'axios'

const http_request = axios.create({
  baseURL: `${location.protocol}//${location.host}/laravel/api`,
  timeout: 16e3,
  headers: {
    'content-type': 'application/json'
  }
})

export default {
  async getSessionId(survey_uuid) {
    return await http_request.get(`${survey_uuid}/session_id`).then(response =>
      response.data.session_id
    )
  }
}
