#!/usr/bin/env python3
TEMPLATE = """\
        %spublic %s %s {
            get {
                return %s_;
            }
            set {
                if (value != %s_) {
                    %s_ = value;
                    NotifyPropertyChanged("%s");
                }
            }
        }
"""
JSONPROPERTY_TEMPLATE = '[JsonProperty]\n        '

def write(has_jsonproperty, vtype, name):
    if has_jsonproperty:
        jsonproperty = JSONPROPERTY_TEMPLATE
    else:
        jsonproperty = ''
    return TEMPLATE % (jsonproperty, vtype, name, name, name, name, name)

properties = [
    '1 string name',
    '0 int another',
    # 12 fields omitted for brevity
]
properties_split = [p.split() for p in properties]

# Private definitions (internal)
for has_jsonproperty, vtype, name in properties_split:
    print("        private %s %s_ { get; set; }" % (vtype, name))

print()
# Public definitions (with notifications)
for has_jsonproperty, vtype, name in properties_split:
    print(write(has_jsonproperty == '1', vtype, name))
