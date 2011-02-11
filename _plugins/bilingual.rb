module Jekyll
  class BilingualTag < Liquid::Tag

    def initialize(tag_name, text, tokens)
      super
      @text = text
    end

    def render(context)
      "#{@text} #{'It is still in development.'}"
    end
  end
end

Liquid::Template.register_tag('bilingual', Jekyll::BilingualTag)
