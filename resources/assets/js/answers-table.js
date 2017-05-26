import $ from 'jquery'

// Table css class symbol
const tccs = Symbol()

// Add answer css class symbol
const aaccs = Symbol()

export default class AnswersTable {
  constructor(table_css_class, add_answer_css_class) {
    const instance = this

    this[tccs] = $(table_css_class)
    this[aaccs] = $(add_answer_css_class)

    this[aaccs].on('click', function() { instance.addAnswer() })
  }

  store() {}

  addAnswer(type = 'check') {
    this[tccs].find('tbody').append(
      this.createRowActions(
        this.createAnswer(type)
      )
    )
    this.normalizeRows()
  }

  restore() {}

  countRows() {
    return this[tccs].find('tbody tr').length
  }

  /************************************************/

  removeRow(index) {
    return this.countRows() > 1 && isFinite(index) && index >= 0 && !!this[tccs].find('tbody tr:eq(' + index + ')').remove()
  }

  createRowActions(row) {
    const instance = this

    row.find('td:eq(2) > div > button:eq(0)').on('click', function(event) {
      event.preventDefault()
      instance.removeRow($(this).parents('tr:eq(0)').index())
      instance.normalizeRows()
    })

    row.find('td:eq(2) > div > button:eq(1)').on('click', function(event) {
      event.preventDefault()
      const $row = $(this).parents('tr:eq(0)');
      $row.after(
        instance.createRowActions(
          instance.createFreeAnswer()
        )
      )
      $row.remove();
      instance.normalizeRows()
    })

    return row
  }

  createAnswer(type = 'check') {
    return type === 'check'
      ? this.createCheckAnswer()
      : this.createFreeAnswer()
  }

  createFreeAnswer() {
    return $('<tr>').append(
      $('<td>'),
      $('<td>').append(
        'Free text'
      ),
      $('<td>').append(
        $('<div>').addClass('pull-right').append(
          $('<button>').addClass('btn btn-danger').text('Remove')
        )
      )
    )
  }

  createCheckAnswer() {
    return $('<tr>').append(
      $('<td>'),
      $('<td>').append(
        $('<input>').addClass('form-control')
      ),
      $('<td>').append(
        $('<div>').addClass('pull-right').append(
          $('<button>').addClass('btn btn-danger').text('Remove'),
          $('<button>').addClass('btn btn-primary').text('Free')
        )
      )
    )
  }

  normalizeRows() {
    this[tccs].find('tbody tr').each(function(index, tr) {
      $(tr).find('td:eq(0)').text(index);
    });
  }
}

