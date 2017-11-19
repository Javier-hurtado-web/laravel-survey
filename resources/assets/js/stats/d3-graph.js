import d3 from 'd3'
import $ from 'jquery'
import _ from 'lodash'

function getData() {
  return $survey_d3_data_json
}

const d3Graph = {
  svg: null,
  drawBars: _ => {
    if(!d3Graph.svg) {
      $('span.svg-loader').remove()
      d3Graph.svg = d3.select('.svg-container').append('svg')
    }

    const svg = d3Graph.svg
    svg.selectAll('*').remove()

    const data = getData()
        , margins = {
            top: 40,
            right: 20,
            bottom: 40,
            left: 50
          }
        , outer_width = $(window).width() > 500 ? 500 : $(window).width() * 0.8
        , outer_height = 250
        , x_column = 'version'
        , y_column = 'total'
        , inner_width = outer_width - margins.left - margins.right
        , inner_height = outer_height - margins.top - margins.bottom
        , x_scale_spaces = 0.3
        , x_scale = d3.scale.ordinal().rangeBands([0, inner_width], x_scale_spaces)
        , y_scale = d3.scale.linear().range([inner_height, 0])
        , colors = d3.scale.category10()
        , g = svg.append('g').attr('transform', 'translate(' + margins.left + ', ' + margins.top + ')')
        , x_axis_g = g.append('g').attr('class', 'd3-axis').attr('transform', 'translate(0, ' + inner_height + ')')
        , x_axis = d3.svg.axis().scale(x_scale).orient('bottom').outerTickSize(0)
        , y_axis_g = g.append('g').attr('class', 'd3-axis').attr('transform', 'translate(0, 0)')
        , y_axis = d3.svg.axis().scale(y_scale).orient('left').ticks(5).outerTickSize(0)
        , y_axis_text_height = 15
        , y_axis_title = 'Answers'
        , y_axis_text = y_axis_g.append('text').style('text-anchor', 'middle').attr('transform', 'translate(' + (-margins.left + y_axis_text_height) + ', ' + (inner_height / 2) + ') rotate(-90)').text(y_axis_title)
        , x_axis_title = 'Versions'
        , x_axis_text = x_axis_g.append('text').style('text-anchor', 'middle').attr('transform', 'translate(' + (inner_width / 2) + ', ' + margins.bottom + ')').text(x_axis_title)

    x_scale.domain(data.map(d => d[x_column]))
    y_scale.domain([0, d3.max(data, d => d[y_column])])

    svg
      .attr('width', outer_width)
      .attr('height', outer_height)

    const bars = g
          .selectAll('rect')
          .data(data)

    x_axis_g.call(x_axis)
    y_axis_g.call(y_axis)

    // Enter
    bars
      .enter()
      .append('rect')
      .attr('x', d => x_scale(d[x_column]))
      .attr('y', inner_height)
      .attr('height', 0)
      .attr('fill', d => colors(d[x_column] + d[y_column]))
      .attr('width', x_scale.rangeBand())
      .on('mouseover', d => {
        const svg_text_over_rect_offset = 5

        g
          .append('text')
          .attr('class', 'svg-text-over-rect')
          .attr('x', x_scale(d[x_column]) + x_scale.rangeBand() / 2.3)
          .attr('y', y_scale(d[y_column]) - svg_text_over_rect_offset)
          .text(d[y_column])
          .style('opacity', 0)
          .transition()
          .duration(400)
          .style('opacity', 1)
      })
      .on('mouseleave', _ =>
        g.selectAll('text.svg-text-over-rect')
          .transition()
          .duration(400)
          .style('opacity', 0)
          .each('end', function() {
            d3.select(this).remove()
          })
      )

    // Update
    bars
      .transition()
      .delay((d, i) => i * 200)
      .duration(1000)
      .attr('y', d => y_scale(d[y_column]))
      .attr('height', d => inner_height - y_scale(d[y_column]))

    // Exit
    bars
      .exit()
      .remove()
  }
}

d3Graph.drawBars = _.debounce(d3Graph.drawBars, 50)

export default d3Graph
