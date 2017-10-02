# utils.py contains a function with all the 

def get_text_from_css(root, css_selector):
    """returns string from desired tag"""
    child_element = root.cssselect(css_selector)
    if child_element:
        return child_element[0].xpath('string()').strip()
    else:
        return ''
