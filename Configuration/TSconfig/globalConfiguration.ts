mod.flex_grid {
	config {
		colCount = 24
		allowed = *

		breakpoints {
			small {
				disabled = 0
				cols = 12,24
				offsets = 0,12
				marginTop = 1-10
			}
			medium {
				disabled = 0
				cols = 8,12,16,24
				offsets = 0,8,12,16
				marginTop = 1-10
			}
			large {
				disabled = 0
				cols = 1-24
				offsets = 0-23
				marginTop = 1-10
			}
			xlarge {
				disabled = 0
				cols = 1-24
				offsets = 0-23
				marginTop = 1-10
			}
		}
		paddings {
			box = Innenabstand
		}
		layouts {
			test = Test123
		}
	}

	# Add predefined configurations.
	predefined {

	}
}